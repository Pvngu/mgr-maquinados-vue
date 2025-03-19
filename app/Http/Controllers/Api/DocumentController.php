<?php

namespace App\Http\Controllers\Api;

use App\Models\State;
use App\Models\Signer;
use GuzzleHttp\Client;
use App\Classes\Common;
use App\Models\Document;
use Illuminate\Support\Str;
use App\Models\PaymentSchedule;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ESignDocTemplate;
use Examyou\RestAPI\ApiResponse;
use GuzzleHttp\Exception\RequestException;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Document\PDFRequest;
use App\Http\Requests\Api\Document\VoidRequest;
use App\Http\Requests\Api\Document\IndexRequest;
use App\Http\Requests\Api\Document\StoreRequest;
use App\Http\Requests\Api\Document\DeleteRequest;
use App\Http\Requests\Api\Document\UpdateRequest;
use App\Http\Requests\Api\Document\ReminderRequest;
use App\Http\Requests\Api\Document\SignEasyRequest;

class DocumentController extends ApiBaseController
{
    protected $model = Document::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    protected function modifyIndex($query)
    {
        $user = user();
        $request = request();

        $query = $query->join('individuals', 'individuals.id', '=', 'documents.individual_id');

        if ($request->has('individual_id') && $request->individual_id != "") {
            $individualId = $this->getIdFromHash($request->individual_id);

            $query = $query->where('documents.individual_id', $individualId);

            if ($request->has('type') && $request->type != "") {
                $query = $query->where('documents.type', $request->type);
            }
        }

        return $query;
    }

    public function storing($document)
    {
        $loggedUser = user();
        $individualId = $document->individual->id;

        if($loggedUser) {
            $document->created_by_id = $loggedUser->id;
        }
        
        $document->type = 'uploaded';

        $notes = json_encode([
            'title' => $document->title,
            'type' => $document->type
        ]);

        Common::storeIndividualLog($individualId, 'doc', $notes);
        
        return $document;
    }

    public function deleteFile($fileName) {
        $filePath = public_path('uploads/documents/' . $fileName);

        if(file_exists($filePath)) {
            unlink($filePath);
        }
    }

    public function generatePDF(PDFRequest $request) {
        $request = request();
        $success = true;

        $pdf = $this->generatePdfFromTemplate(
            $request->individualId, 
            $request->template_id,
            'generated'
        );

        if(!$pdf) {
            $success = false;
        }

        return ApiResponse::make('Success', [
            'success' => $success,
        ]);
    }

    public function sendEnvelope(SignEasyRequest $request) {
        $request = request();
        $success = true;

        try {
            $client = new Client();
            $recipients = [];
            $field_payload = [];

            $pdf = $this->generatePdfFromTemplate(
                $request->individualId, 
                $request->template_id,
                'e-sign'
            );

            // Add Signers and Fields
            foreach($request->signers as $index => $signer) {
                $recipients[] = [
                    "first_name" => $signer['first_name'],
                    "last_name" => $signer['last_name'],
                    "email" => $signer['email'],
                    "recipient_id" => $index + 1,
                ];

                $field_payload[] = $this->createFieldPayload($index + 1, 'signature', $signer['type']);
                
                $field_payload[] = $this->createFieldPayload($index + 1, 'date', $signer['type']);
            }
            
            $originalResponse = $client->request('POST', 'https://api.signeasy.com/v3/original/', [
                'multipart' => [
                    [
                        'name'     => 'name',
                        'contents' => "{$pdf['file_name']}.pdf"
                    ],
                    [
                        'name'     => 'rename_if_exists',
                        'contents' => true
                    ],
                    [
                        'name'     => 'file',
                        'contents' => fopen(public_path('uploads/documents/' . $pdf['file_name'] . '.pdf'), 'r'),
                        'filename' => "{$pdf['file_name']}.pdf"
                    ],
                ],
                'headers' => [
                    'accept' => 'application/json',
                    'Authorization' => 'Bearer ' . env('SIGNEASY_ACCESS_TOKEN'),
                ],
            ]);

            $original = json_decode($originalResponse->getBody(), true);

            $envelopeResponse = $client->request('POST', 'https://api.signeasy.com/v3/rs/envelope/', [
                'body' => json_encode([
                'embedded_signing' => false,
                'is_ordered' => $request->signOrder,
                'message' => $request->message,
                'sources' => [
                    [
                    'type' => 'original',
                    'name' => $request->subject,
                    'id' => $original['id'],
                    'source_id' => 1
                    ]
                ],
                "recipients" => $recipients,
                "fields_payload" => $field_payload,
                ]),
                'headers' => [
                'accept' => 'application/json',
                'content-type' => 'application/json',
                'Authorization' => 'Bearer ' . env('SIGNEASY_ACCESS_TOKEN'),
                ],
            ]);

            $envelope = json_decode($envelopeResponse->getBody(), true);

            // Store signers
            foreach($recipients as $signer) {
                $signerModel = new Signer();
                $signerModel->document_id = $pdf['document_id'];
                $signerModel->first_name = $signer['first_name'];
                $signerModel->last_name = $signer['last_name'];
                $signerModel->email = $signer['email'];
                $signerModel->save();
            }

            // Update document record
            $document = Document::find($pdf['document_id']);
            $document->signeasy_document_id = $envelope['id'];
            $document->save();
            } catch (\Exception $e) {
                $success = false;
            }

        return ApiResponse::make('Success', [
            'success' => $success,
        ]);
    }

    public function createFieldPayload($recipientId, $type, $signerType) {
        return [
            'recipient_id' => $recipientId,
            'source_id' => 1,
            'type' => $type,
            'required' => true,
            'page_number' => 'all',
            'position' => [
                'mode' => 'referenceText', 
                'height' => 40, 
                'width' => 100, 
                "yOffset"=> -45,
                'text' => $signerType == 'applicant' ? '{A_' . strtoupper($type) . '}' : '{CO_' . strtoupper($type) .'}']
        ];
    }

    public function downloadEnvelope($id, $type, $includeCertificate) {
        $client = new Client();
    
        $url = '';
        if($type == 'complete') {
            $url = 'https://api.signeasy.com/v3/signed/'. $id . '/download?type=merged&include_certificate=' . $includeCertificate;
        }

        if($type == 'audit') {
            $url = 'https://api.signeasy.com/v3/rs/envelope/signed/' . $id .'/certificate';
        }

        if($type == 'incomplete') {
            $url = 'https://api.signeasy.com/v3/rs/embedded/' . $id . '/download/';
        }

        $response = $client->request('GET', $url, [
            'headers' => [
              'accept' => 'application/json',
              'Authorization' => 'Bearer ' . env('SIGNEASY_ACCESS_TOKEN'),
            ],
        ]);

        return $response->getBody();
    }

    public function downloadPendingEnvelope($pending_file_id) {
        $client = new Client();

        $response = $client->request('GET', 'https://api.signeasy.com/v3/rs/embedded/' . $pending_file_id . '/download/', [
            'headers' => [
              'accept' => 'application/json',
              'Authorization' => 'Bearer ' . env('SIGNEASY_ACCESS_TOKEN'),
            ],
        ]);

        return $response->getBody();
    }

    public function voidEnvelope(VoidRequest $request) {
        $request = request();
        $success = true;
        $client = new Client();
        $reason = $request->reason ?? 'voided';
        $loggedUser = user();

        $docXid = $request->document_id;
        $docId = $this->getIdFromHash($docXid);
        $doc = Document::find($docId);

        try {
            $client->request('POST', 'https://api.signeasy.com/v3/rs/envelope/' . $doc->signeasy_document_id . '/cancel', [
                'body' => json_encode([
                    'message' => $reason,
                ]),
                'headers' => [
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                    'authorization' => 'Bearer ' . env('SIGNEASY_ACCESS_TOKEN'),
                ],
            ]);

            $doc->updated_by_id = $loggedUser->id;
            $doc->status = 'canceled';
            $doc->save();

            $notes = json_encode([
                'title' => $doc->title,
                'type' => 'e-sign'
            ]);

            Common::storeIndividualLog($doc->individual_id, 'voided_doc', $notes);

        } catch (\Exception $e) {
            $success = false;
        }

        return ApiResponse::make('Success', [
            'success' => $success,
        ]);
    }

    public function sendReminder(ReminderRequest $request) {
        $request = request();
        $client = new Client();
        $success = true;

        $id = $request->file_id;

        $statusCode = 200;

        try {
            $response = $client->request('POST', 'https://api.signeasy.com/v3/rs/envelope/' . $id . '/remind/', [
                'headers' => [
                  'accept' => 'application/json',
                  'authorization' => 'Bearer ' . env('SIGNEASY_ACCESS_TOKEN'),
                ],
            ]);
        } catch (\Exception $e) {
            $success = false;

            if ($e->hasResponse()) {
                // Get the response code
                $statusCode = $e->getResponse()->getStatusCode();
            }
        }

        return ApiResponse::make('Success', [
            'success' => $success,
            'status_code' => $statusCode,
        ]);
    }

    public function fetchEnvelopes($individualXid) {
        $client = new Client();
        $user = user();
        $success = true;
        
        $count = 0;
        $individual_id = $this->getIdFromHash($individualXid);
        $envelopes = Document::where('individual_id', $individual_id)
                                ->where('type', 'e-sign')
                                ->where('status', 'incomplete')
                                ->get();

        foreach($envelopes as $envelope) {
            $count++;
            try {
                $response = $client->request('GET', 'https://api.signeasy.com/v3/rs/envelope/' . $envelope->signeasy_document_id, [
                    'headers' => [
                      'accept' => 'application/json',
                      'authorization' => 'Bearer ' . env('SIGNEASY_ACCESS_TOKEN'),
                    ],
                ]);

                $envelopeData = json_decode($response->getBody(), true);

                if($response->getStatusCode() === 200) {
                    $this->updateEnvelope(  $envelope, $envelopeData['status'], $envelopeData['recipients']);
                }
                
            } catch (\Exception $e) {
                $success = false;
            }
        }

        return ApiResponse::make('Success', [
            'success' => $success,
            'count' => $count,
        ]);
    }

    public function updateEnvelope ($envelope, $status, $recipients) {

        $envelope->status = $status;
        $envelope->save();

        if($status == 'complete') {
            Common::createNotification('signed_document', documentId: $envelope->id, receiverId: $envelope->created_by_id, individualId: $envelope->individual_id);
        }
        
        foreach($recipients as $recipient) {
            $signer = Signer::where('document_id', $envelope->id)
                            ->where('email', $recipient['email'])
                            ->first();

            if($signer) {
                $signer->status = $recipient['status'];
                $signer->last_modified_time = $recipient['last_modified_time'];
                $signer->save();
            }
        }
    }

    public function generatePdfFromTemplate($individualXid, $templateXid, $type) {
        $loggedUser = user();

        $individualId = $this->getIdFromHash($individualXid);
        $individual = Individual::find($individualId);

        $templateId = $this->getIdFromHash($templateXid);
        $template = ESignDocTemplate::find($templateId);

        $includeArr = $template->include;

        $data = [
            'includeArr' => $includeArr,
        ];

        if (in_array('applicant', $includeArr)) {
            $data = array_merge($data, [
            'reference_number' => $individual->reference_number ?? null,
            'first_name' => $individual->first_name ?? null,
            'last_name' => $individual->last_name ?? null,
            'SSN' => $individual->SSN ?? null,
            'date_of_birth' => $individual->date_of_birth ? \Carbon\Carbon::parse($individual->date_of_birth)->format('m/d/Y') : null,
            'phone_number' => $individual->phone_number ?? null,
            'home_phone' => $individual->home_phone ?? null,
            'email' => $individual->email ?? null,
            'language' => $individual->language ?? null,
            'original_profile_id' => $individual->original_profile_id ?? null,
            ]);
        }

        if (in_array('applicant_address', $includeArr)) {
            $data = array_merge($data, [
            'address_line1' => $individual->address->address_line1 ?? null,
            'address_line2' => $individual->address->address_line2 ?? null,
            'city' => $individual->address->city ?? null,
            'state' => $individual->bankAccount ? State::find($individual->bankAccount->state_id)->code : null,
            'zip_code' => $individual->address->zip_code ?? null,
            ]);
        }

        if(in_array('co_applicant', $includeArr)) {
            $data = array_merge($data, [
            'co_first_name' => $individual->coApplicant->first_name ?? null,
            'co_last_name' => $individual->coApplicant->last_name ?? null,
            'co_SSN' => $individual->coApplicant->SSN ?? null,
            'co_date_of_birth' => ($individual->coApplicant && $individual->coApplicant->date_of_birth) ? \Carbon\Carbon::parse($individual->coApplicant->date_of_birth)->format('m/d/Y') : null,
            'co_phone_number' => $individual->coApplicant->phone_number ?? null,
            'co_home_phone' => $individual->coApplicant->home_phone ?? null,
            'co_email' => $individual->coApplicant->email ?? null,
            'co_language' => $individual->coApplicant->language ?? null,
            ]);
        }

        if(in_array('bank', $includeArr)) {
            $data = array_merge($data, [
            'routing_number' => $individual->bankAccount->routing_number ?? null,
            'payment_method' => $individual->bankAccount->payment_method ?? null,
            'account_number' => $individual->bankAccount->account_number ?? null,
            'account_type' => $individual->bankAccount->account_type ?? null,
            'bank_name' => $individual->bankAccount->bank_name ?? null,
            'account_holder_name' => $individual->bankAccount->account_holder_name ?? null,
            'account_holder_address' => $individual->bankAccount->account_holder_address ?? null,
            'account_holder_city' => $individual->bankAccount->account_holder_city ?? null,
            'account_holder_state' => $individual->bankAccount ? State::find($individual->bankAccount->state_id)->code : null,
            'account_holder_zipcode' => $individual->bankAccount->account_holder_zipcode ?? null,
            ]);
        }

        if(in_array('debts', $includeArr)) {
            if ($individual->debts) {
                $debts = $individual->debts->whereNotNull('enrollment_id');
            
                $total = 0;
                foreach($debts as $debt) {
                    $total += $debt->current_amount;
                }
    
                $data = array_merge($data, [
                'debts' => $debts,
                'total_debt' => $total,
                ]);
            } else {
                $data = array_merge($data, [
                    'debts' => [],
                    'total_debt' => null,
                ]);
            }
        }

        if(in_array('payment_schedule', $includeArr)) {
            if ($individual->enrollment) {
                $payment_schedules = PaymentSchedule::where('enrollment_id', $individual->enrollment->id)->get();

                $total_interest = 0;
                $total_principal = 0;
                foreach($payment_schedules as $schedule) {
                    $total_interest += $schedule->interest_amount;
                    $total_principal += $schedule->principal_amount;
                }
    
                $data = array_merge($data, [
                'payment_schedules' => $payment_schedules,
                'next_payment_date' => \Carbon\Carbon::parse($payment_schedules->first()->due_date)->format('F jS, Y'),
                'total_interest' => $total_interest,
                'total_principal' => $total_principal,
                ]);
            } else {
                $data = array_merge($data, [
                    'payment_schedules' => [],
                    'next_payment_date' => null,
                    'total_interest' => null,
                    'total_principal' => null,
                ]);
            }
        }

        try {
            // Create PDF view
            $pdf = Pdf::loadView("pdf_templates.$template->template_id", $data)
                        ->setPaper('letter' , 'portrait');

            if($pdf) {
                $fileName   = 'documents' . '_' . strtolower(Str::random(20));
                $pdf->save(public_path('uploads/documents/' . $fileName .'.pdf'));

                // Create new document record
                $document = New Document();

                $document->individual_id = $individualId;
                $document->title = $template->name;
                $document->type = $type;
                $document->status = $type == 'generated' ? null : 'incomplete';
                $document->file = $fileName . '.pdf';
                $document->created_by_id = $loggedUser->id;
                $document->save();
            }

            $noteType = $type == 'generated' ? 'generated_doc' : 'esign_doc';

            $notes = json_encode([
                'title' => $document->title,
                'type' => $noteType
            ]);

            Common::storeIndividualLog($individualId, $noteType, $notes);
            
            return [
                'document_id' => $document->id,
                'file_name' => $fileName,
            ];
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function signeasyWebhook() {
        $request = request();
        $envelope = $request->all();

        $envelopeId = $envelope['data']['id'];
        $envelopeStatus = $envelope['data']['status'];
        $envelopeRecipients = $envelope['data']['recipients'];

        $document = Document::where('signeasy_document_id', $envelopeId)->first();

        if(!$document) {
            return response()->json(['success' => false], 400);
        }
        
        if($document->status !== 'complete') {
            $this->updateEnvelope($document, $envelopeStatus, $envelopeRecipients);
        }
        
        return response()->json(['success' => true], status: 200);
    }
}
