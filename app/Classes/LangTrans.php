<?php

namespace App\Classes;

use App\Models\Lang;
use App\Models\Translation;
use Nwidart\Modules\Facades\Module;

class LangTrans
{
    public static $mainTranslations = [
        'common' => [
            'enabled' => 'Enabled',
            'disabled' => 'Disabled',
            'id' => 'Id',
            'action' => 'Action',
            'placeholder_default_text' => 'Please Enter {0}',
            'placeholder_social_text' => 'Please Enter {0} Url',
            'placeholder_search_text' => 'Search By {0}',
            'select_default_text' => 'Select {0}...',
            'create' => 'Create',
            'edit' => 'Edit',
            'update' => 'Update',
            'cancel' => 'Cancel',
            'delete' => 'Delete',
            'success' => 'Success',
            'error' => 'Error',
            'yes' => 'Yes',
            'no' => 'No',
            'fix_errors' => 'Please Fix Below Errors.',
            'cancelled' => 'Cancelled',
            'pending' => 'Pending',
            'paid' => 'Paid',
            'canceled' => 'Canceled',
            'completed' => 'Completed',
            'rejected' => 'Rejected',
            'save' => 'Save',
            'all' => 'All',
            'name' => 'Name',
            'back' => 'Back',
            'max_amount' => 'Max. Amount',
            'date_time' => 'Date Time',
            'select_time' => 'Select Time',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'search' => 'Search',
            'date' => 'Date',
            'confirm' => 'Confirm',
            'title' => 'Title',
            'value' => 'Value',
            'add' => 'Add',
            'view' => 'View',
            'download' => 'Download',
            'total' => 'Total',
            'email' => 'Email',
            'phone' => 'Phone',
            'purchase_code' => 'Purchase Code',
            'verify_purchase' => 'Verify Purchase',
            'install' => 'Install',
            'installing' => 'Installing',
            'updating' => 'Updating',
            'free' => 'Free',
            'domain' => 'Domain',
            'verify' => 'Verify',
            'send' => 'Send',
            'upload' => 'Upload',
            'view_all' => 'View All',
            'unpaid' => 'Unpaid',
            'loading' => 'Loading',
            'update_app' => 'Update App',
            'welcome_back' => 'Welcome {0}',
            'off' => 'Off',
            'on_create' => 'On Create',
            'on_update' => 'On Update',
            'on_delete' => 'On Delete',
            'demo_account_credentials' => 'Demo account login credentials',
            'created_by' => 'Created By',
            'import' => 'Import',
            'file' => 'File',
            'copy_url' => 'Copy Url',
            'print' => 'Print',
            'amount' => 'Amount',
            'status' => 'Status',
            'active' => 'Active',
            'inactive' => 'Inactive',
            'verified' => 'Verified',
            'configure' => 'Configure',
            'logo' => 'Logo',
            'slug' => 'Slug',
            'description' => 'Description',
            'image' => 'Image',
            'not_allowed' => 'Not Allowed',
            'details' => 'Details',
            'text' => 'Text',
            'link' => 'Link',
            'start' => 'Start',
            'stop' => 'Stop',
            'are_you_sure' => 'Are you sure',
            'basic_details' => 'Basic Details',
            'more' => 'More',
            'other' => 'Other',
            'continue' => 'Continue',
            'docs' => 'Docs',
            'e_sign' => 'Signeasy',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'close' => 'Close',
            'template_id' => 'Template ID',
            'include' => 'Include',
            'today' => 'Today',
            'general' => 'General',
            'unknown' => 'Unknown',
            'export' => 'Export',
            'columns' => 'Columns',
            'date_range' => 'Date Range',
            'include_all_as_default' => '(Include All As Default)',
            'export_table' => 'Export {0}',
            'export_as' => 'Export As',
        ],
        'menu' => [
            'dashboard' => 'Dashboard',
            'users' => 'Users',
            'staff_members' => 'Staff Members',
            'settings' => 'Settings',
            'company' => 'Company Settings',
            'profile' => 'Profile',
            'translations' => 'Translations',
            'languages' => 'Languages',
            'roles' => 'Role & Permissions',
            'currencies' => 'Currencies',
            'login' => 'Login',
            'logout' => 'Logout',
            'storage_settings' => 'Storage Settings',
            'email_settings' => 'Email Settings',
            'database_backup' => 'Database Backup',
            'companies' => 'Companies',
            'email_templates' => 'Email Templates',
            'forms'  => 'Forms',
            'user_management' => 'User Management',
            'messaging' => 'Messaging',
            'setup_company' => 'Setup Company',
            'form_field_names' => 'Lead Table Fields',
            'templates' => 'Templates',
            'categories' => 'Categories',
            'suppliers' => 'Suppliers',
            'products' => 'Products',
        ],
        'dashboard' => [
            'dashboard' => 'Dashboard',
        ],
        'user' => [
            'staff_members' => 'Staff Members',
            'add' => 'Add New Staff Member',
            'edit' => 'Edit Staff Member',
            'created' => 'Staff Member Created Successfully',
            'updated' => 'Staff Member Updated Successfully',
            'deleted' => 'Staff Member Deleted Successfully',
            'staff_member_details' => 'Staff Member Details',
            'staff_member' => 'Staff Member',
            'delete_message' => 'Are you sure you want to delete this staff member?',
            'selected_delete_message' => 'Are you sure you want to delete selected staff member?',
            'import_staff_members' => 'Import Staff Members',
            'email_phone' => 'Email or Phone',
            'user' => 'User',
            'name' => 'Name',
            'created_at' => 'Created At',
            'email' => 'Email',
            'password' => 'Password',
            'login_enabled' => 'Login Enabled',
            'profile_image' => 'Profile Image',
            'company_name' => 'Company Name',
            'phone' => 'Phone Number',
            'address' => 'Address',
            'city' => 'City',
            'state' => 'State',
            'country' => 'Country',
            'zipcode' => 'Zipcode',
            'status' => 'Status',
            'role' => 'Role',
            'profile_updated' => 'Profile Updated Successfully',
            'password_blank' => "Leave blank if you don't want to update password.",
            'admin_account_details' => "Admin Account Details",
            'import_users' => 'Import Users',
            'staff_members_details' => 'Staff Members Details',
            'export_users' => 'Export Users',
            'time_taken' => 'Time Taken',
            'count_assigned_sales' => 'Count Assigned Sales',
            'count_sent_sms_messages' => 'Count Sent SMS Messages',
            'roles' => 'Roles',
        ],
        'role' => [
            'add' => 'Add New Role',
            'edit' => 'Edit Role',
            'created' => 'Role Created Successfully',
            'updated' => 'Role Updated Successfully',
            'deleted' => 'Role Deleted Successfully',
            'role_details' => 'Role Details',
            'delete_message' => 'Are you sure you want to delete this role?',
            'selected_delete_message' => 'Are you sure you want to delete selected role?',
            'display_name' => 'Display Name',
            'role_name' => 'Role Name',
            'description' => 'Description',
            'user_management' => 'User Management',
            'permissions' => 'Permissions',
        ],
        'email_template' => [
            'add' => 'Add New Email Template',
            'edit' => 'Edit Email Template',
            'created' => 'Email Template Created Successfully',
            'updated' => 'Email Template Updated Successfully',
            'deleted' => 'Email Template Deleted Successfully',
            'email_template_details' => 'Email Template Details',
            'delete_message' => 'Are you sure you want to delete this email template?',
            'selected_delete_message' => 'Are you sure you want to delete selected email template?',
            'name' => 'Name',
            'subject' => 'Subject',
            'body' => 'Body',
            'message' => 'Message',
            'status' => 'Status',
            'sharable' => 'Sharable',
            'sharable_message' => 'Is this template sharable with other team members',
            'status_update' => 'Status Updated Successfully',
            'mail_send_successfully' => 'Email Sent Successfully',
            'mail_send_error_message' => 'Contact admin to setup mail settings',
            'additionals' => 'Additionals',
        ],
        'staff_member' => [
            'add' => 'Add New Staff Member',
            'edit' => 'Edit Staff Member',
            'created' => 'Staff Member Created Successfully',
            'updated' => 'Staff Member Updated Successfully',
            'deleted' => 'Staff Member Deleted Successfully',
            'staff_member_details' => 'Staff Member Details',
            'staff_member' => 'Staff Member',
            'delete_message' => 'Are you sure you want to delete this staff member?',
            'selected_delete_message' => 'Are you sure you want to delete selected staff member?',
            'import_staff_members' => 'Import Staff Members',
        ],
        'form' => [
            'add' => 'Add New Form',
            'edit' => 'Edit Form',
            'created' => 'Form Created Successfully',
            'updated' => 'Form Updated Successfully',
            'deleted' => 'Form Deleted Successfully',
            'form_details' => 'Form Details',
            'delete_message' => 'Are you sure you want to delete this form?',
            'selected_delete_message' => 'Are you sure you want to delete selected form?',
            'form' => 'Form',
            'name' => 'Name',
            'status' => 'Status',
            'form_fields' => 'Form Fields',
            'add_form_field' => 'Add Form Field',
            'field_name' => 'Field Name',
            'status_update' => 'Status Updated Successfully',
        ],
        'form_field_name' => [
            'add' => 'Add New Lead Table Field',
            'edit' => 'Edit Lead Table Field',
            'created' => 'Lead Table Field Created Successfully',
            'updated' => 'Lead Table Field Updated Successfully',
            'deleted' => 'Lead Table Field Deleted Successfully',
            'form_field_name_details' => 'Lead Table Field Details',
            'delete_message' => 'Are you sure you want to delete this Form Field Name?',
            'selected_delete_message' => 'Are you sure you want to delete selected Form Field Name?',
            'field_name' => 'Field Name',
            'similar_field_names' => 'Similar Field Names',
            'visible' => 'Visible',
            'add_field' => 'Add Field',
            'status_update' => 'Status Updated Successfully',
        ],
        'company' => [
            'add' => 'Add New Company',
            'edit' => 'Edit Company',
            'created' => 'Company Created Successfully',
            'updated' => 'Company Updated Successfully',
            'deleted' => 'Company Deleted Successfully',
            'currency_details' => 'Company Details',
            'delete_message' => 'Are you sure you want to delete this company?',
            'name' => 'Company Name',
            'short_name' => 'Company Short Name',
            'email' => 'Company Email',
            'phone' => 'Company Phone',
            'address' => 'Company Address',
            'currency' => 'Currency',
            'logo' => 'Company Logo',
            'left_sidebar_theme' => 'Left Sidebar Theme',
            'dark' => 'Dark',
            'light' => 'Light',
            'dark_logo' => 'Dark Logo',
            'light_logo' => 'Light Logo',
            'small_dark_logo' => 'Small Dark Logo',
            'small_light_logo' => 'Small Light Logo',
            'primary_color' => 'Primary Color',
            'default_timezone' => 'Default Timezone',
            'date_format' => 'Date Format',
            'time_format' => 'Time Format',
            'auto_detect_timezone' => 'Auto Detect Timezone',
            'app_debug' => 'App Debug',
            'login_image' => 'Login Image',
            'layout' => 'Layout',
            'rtl' => 'RTL',
            'ltr' => 'LTR',
            'shortcut_menu_Placement' => 'Add Menu Placement',
            'top_and_bottom' => 'Top & Bottom',
            'top_header' => 'Top Header',
            'bottom_corner' => 'Bottom Conrer',
            'shortcut_menu_setting' => 'Add Menu Settings',
            'menu_setting_updated' => 'Menu Setting Updated',
            'basic_details' => 'Basic Details',
            'details' => 'Details',
            'register_date' => 'Register Date',
            'total_users' => 'Total Users',
            'update_app_notification' => 'Update App Notitication',
            'language' => 'Language'
        ],
        'currency' => [
            'add' => 'Add New Currency',
            'edit' => 'Edit Currency',
            'created' => 'Currency Created Successfully',
            'updated' => 'Currency Updated Successfully',
            'deleted' => 'Currency Deleted Successfully',
            'currency_details' => 'Currency Details',
            'delete_message' => 'Are you sure you want to delete this currency?',
            'selected_delete_message' => 'Are you sure you want to delete selected currency?',
            'name' => 'Currency Name',
            'symbol' => 'Currency Symbol',
            'position' => 'Currency Position',
            'front' => 'Front',
            'front_position_example' => 'Example : $100',
            'behind' => 'Behind',
            'behind_position_example' => 'Example : 100$',
            'code' => 'Currency Code',
        ],
        'langs' => [
            'add' => 'Add New Language',
            'edit' => 'Edit Language',
            'details' => 'Language Details',
            'created' => 'Language Created Successfully',
            'updated' => 'Language Updated Successfully',
            'deleted' => 'Language Deleted Successfully',
            'delete_message' => 'Are you sure you want to delete this language?',
            'view_all_langs' => 'View All Languages',
            'status_updated' => 'Langugage status updated',
            'name' => 'Name',
            'key' => 'Key',
            'flag' => 'Flag',
            'enabled' => 'Enabled',
        ],
        'translations' => [
            'fetch_new_translations' => 'Fetch New Translations',
            'reload_translations' => 'Reload Translations',
            'reload_successfully' => 'Translations Reload Successfully',
            'fetched_successfully' => 'Translations Fetch Successfully',
            'import_translations' => 'Import Translations',
        ],
        'storage_settings' => [
            'updated' => 'Storage Settings Updated',
            'storage' => 'Storage',
            'local' => 'Local',
            'aws' => 'AWS S3 Storage',
            'aws_key' => 'AWS Key',
            'aws_secret' => 'AWS Secret',
            'aws_region' => 'AWS Region',
            'aws_bucket' => 'AWS Bucket',
        ],
        'mail_settings' => [
            'updated' => 'Mail Settings Updated',
            'mail_driver' => 'Mail Driver',
            'none' => 'None',
            'mail' => 'Mail',
            'smtp' => 'SMTP',
            'from_name' => 'Mail From Name',
            'from_email' => 'Mail From Email',
            'host' => 'Host',
            'port' => 'Port',
            'encryption' => 'Encryption',
            'username' => 'Username',
            'password' => 'Password',
            'send_test_mail' => 'Send Test Mail',
            'send_mail_setting_saved' => 'Send mail setting saved',
            'enable_mail_queue' => 'Enable Mail Queue',
            'send_mail_for' => 'Send Mail For',
            'email' => 'Email address for which you want to send test mail',
            'test_mail_sent_successfully' => 'Test mail sent successfully',
            'notificaiton_will_be_sent_to_company' => 'Notification will be sent to company email',
        ],
        'database_backup' => [
            'file' => 'File',
            'file_size' => 'File Size',
            'generate_backup' => 'Generate Backup',
            'delete_backup' => 'Delete Backup',
            'backup_generated_successfully' => 'Backup Generated Successfully',
            'are_you_sure_generate_backup' => 'Are you sure you want to generate database backup?',
            'are_you_sure_delete_backup' => 'Are you sure you want to delete this database backup?',
            'backup_locaion_is' => 'All generated database file will be stored in storage/app/public/backup folder. ',
            'settings' => 'Command Settings',
            'backup_command_setting' => 'Backup Command Settings',
            'mysqldump_command_path' => 'mysqldump command path',
            'command_updated' => 'Command updated successfully',
            'window_command_path' => 'If you use XAMPP then it will be => C:\xampp\mysql\bin\mysqldump.exe',
            'laragon_command_path' => 'If you use Laragon then it will be => C:\laragon\bin\mysql\mysql-5.7.24-winx64\bin\mysqldump.exe',
            'linux_command_path' => 'If you are on ubuntu or mac then run following command and enter output here => which mysqldump',
            'put_dump_path_command_on_env_file' => 'Find your MySQL dump path from below and then add it to the DUMP_PATH inside .env file',
        ],
        'messages' => [
            'somehing_went_wrong' => 'Something went wrong. Please contact to administrator.',
            'verify_success' => 'Successfully verified. Redirect to app...',
            'login_success' => 'Successfully login. Redirect to app...',
            'login_success_dashboard' => 'Successfully logged into app.',
            'click_here_to_find_purchase_code' => 'Click here to find your purchase code',
            'verification_successfull' => 'Verification successfully',
            'other_domain_linked' => 'Other domain linked',
            'other_domain_linked_with_purchase_code' => 'Other domain is already linked with your purchase code. Please enter your purchase code for more details...',
            'first_verify_module_message' => 'To enable please \n verify this module',
            'are_you_sure_install_message' => 'Are you sure you want to install?',
            'downloading_started_message' => 'Downloading started. Please wait ...',
            'file_extracting_message' => 'File extracteding. Please wait ...',
            'installation_success' => 'Installation successfully. Click here to reload page...',
            'are_you_sure_update_message' => 'Are you sure you want to update? Please take backup before update.',
            'stmp_success_message' => 'Your SMTP settings are correct..',
            'stmp_error_message' => 'Your SMTP settings are incorrect. Please update it to send mails',
            'uploading_failed' => 'Uploading failed',
            'loading_app_message' => 'Loading... Please wait',
            'fetching_product_details' => 'We are fetching product details. Please wait...',
            'product_is_upto_date' => 'You are on the latest version of app.',
            'new_app_version_avaialbe' => 'New app version {0} is available. Please update to get latest version.',
            'not_able_to_edit_order' => 'Only order status editable, other fields can not be editable becuase this order linked to some payments. Delete those payment(s) and try again.',
            'click_here_to_download_sample_file' => 'Click here to download sample csv file',
            'imported_successfully' => 'Imported Successfully',
            'company_admin_password_message' => 'Admin will login using this password. (Leave blank to keep current password)',
            'email_setting_not_configured' => 'Email setting not configured',
            'please_configure_email_settings' => 'Please configure your email settings to send emails. Click Here to configure email settings.',
        ],
        'popover' => [
            'auto_detect_timezone' => 'Allow auto detect timezone from browser for currently logged in user.',
            'click_here_to_copy_credentials' => 'Click here to copy {0} credentials',
        ],
        'topbar_add_button' => [
            'add_staff_member' => 'Add Staff Member',
            'add_currency' => 'Add Currency',
            'add_language' => 'Add Language',
            'add_role' => 'Add Role',
        ],
        'setup_company' => [
            'setup_not_completed' => 'Setup Not Completed',
            'setup_not_completed_description' => 'Your company default settings not completed. Follow below setups to complete your company basic settings...',
            'currency' => 'Currency',
            'add_first_currency' => 'Add First Currency',
            'company_settings' => 'Company Settings',
            'set_company_basic_settings' => 'Setup Company Basic Settings',
            'previous_step' => 'Previous Step',
            'next_step' => 'Next Step',
            'basic_settings' => 'Basic Settings',
            'theme_settings' => 'Theme Settings',
            'logo_settings' => 'Logo Settings',
            'save_finish_setup' => 'Save & Finish Setup',
            'go_to_dashboard' => 'Go To Dashboard',
            'setup_running_message' => 'Please wait... we are setting up inital company settings.',
            'setup_complete_message' => 'Setup completed... Click on below link to view your app...',
        ],
        'docs' => [
            'title' => 'Document Title',
            'created_at' => 'Created At',
            'drag_n_drop' => 'Drag and drop files, or Browse',
            'delete_message' => 'Are you sure you want to delete this document?',
            'deleted' => 'Document Deleted Successfully',
            'deleted_error' => 'Document not deleted. Please try again',
            'add' => 'New Document',
            'edit' => 'Edit Document',
            'created' => 'Document Created Successfully',
            'updated' => 'Document Updated Successfully',
            'generated_successfully' => 'Document Generated Successfully',
            'generate_error_message' => 'Document not generated. Please try again',
            'esign_successfully' => 'Document sent via Signeasy Successfully',
            'esign_error_message' => 'Document not sent via Signeasy. Please try again',
            'selected_delete_message' => 'Are you sure you want to delete selected Documents?',
            'doc_timeline_message' => 'Document uploaded by {0} on {1}',
            'doc_timeline_message_deleted' => 'Document deleted by {0} on {1}',
            'generated_doc_timeline_message' => 'Document generated by {0} on {1}',
            'esign_doc_timeline_message' => 'Document sent via Signeasy by {0} on {1}',
            'voided_doc_timeline_message' => 'Document voided by {0} on {1}',
            'generated' => 'Generated Docs',
            'esign' => 'Signeasy Docs',
            'uploaded' => 'Uploaded Files',
            'sent_by' => 'Sent By',
            'signers' => 'Signers',
            'signers_left' => 'Signers Left',
            'sent_at' => 'Sent At',
            'generate_documents' => 'Generate Documents',
            'send_doc_esign' => 'Send Document via Signeasy',
            'generate_save_doc' => 'Generate and Save Document',
            'configuration' => 'Document Configuration',
            'select_template' => 'Select Template To Use',
            'template' => 'Template',
            'delivery_expiration' => 'Delivery & Expiration Settings',
            'send_via' => 'Send Via',
            'expiration_date' => 'Expiration Date',
            'document_expires_on' => 'Document Expires On',
            'defautl_expiration_in' => 'Default Expiration In 30 Days',
            'sms_templates' => 'SMS Templates',
            'send_for_signature' => 'Send For Signature',
            'preview' => 'Preview',
            'generate_save' => 'Generate & Save',
            'assign_doc_signers' => 'Assign Document Signers',
            'enforce_signing_order' => 'Enforce Signing Order',
            'signer_type' => 'Signer Type',
            'signer_name' => 'Name',
            'signer_email' => 'Email',
            'signer_phone' => 'Phone',
            'add_signer' => 'Add Signer',
            'void' => 'Void',
            'voiding_reason' => 'Reason for voiding request',
            'voiding_description' => 'Once voided, signers can no longer access this document. They will receive an email notification, along with your reason for voiding this request.',
            'add_your_reason' => 'Add your reason here (max {0} characters)',
            'signature_request_voided' => 'Signature request voided',
            'error_voiding_signature_request' => 'Error voiding signature request',
            'refresh' => 'Refresh',
            'signed_document_with_audit_trail' => 'Signed Document with Audit Trail',
            'signed_document' => 'Signed Document',
            'audit_trail' => 'Audit Trail',
            'send_reminder' => 'Send Reminder',
            'reminder_sent' => 'Reminder sent successfully',
            'error_sending_reminder' => 'Error sending reminder',
            'waiting' => 'Waiting',
            'signed' => 'Signed',
            'declined' => 'Declined',
            'expired' => 'Expired',
            'voided' => 'Voided',
            'reminder_too_many_error' => 'You have already sent the reminder(s), please wait for {0} hours.',
            'refresh_successfully' => 'Documents refreshed successfully',
            'waiting_for_signature' => 'Waiting for Signature',
            'unknown_date' => 'Unknown Date',
            'for' => 'for',
            'by' => 'by',
            'viewed' => 'Viewed',
        ],
        'categories' => [
            'add' => 'Add New Category',
            'edit' => 'Edit Category',
            'created' => 'Category Created Successfully',
            'updated' => 'Category Updated Successfully',
            'deleted' => 'Category Deleted Successfully',
            'delete_message' => 'Are you sure you want to delete this category?',
            'selected_delete_message' => 'Are you sure you want to delete selected categories?',
        ],
        'suppliers' => [
            'add' => 'Add New Supplier',
            'edit' => 'Edit Supplier',
            'created' => 'Supplier Created Successfully',
            'updated' => 'Supplier Updated Successfully',
            'deleted' => 'Supplier Deleted Successfully',
            'delete_message' => 'Are you sure you want to delete this supplier?',
            'selected_delete_message' => 'Are you sure you want to delete selected suppliers?',
            'contact_name' => 'Contact Name',
            'contact_email' => 'Contact Email',
            'contact_phone' => 'Contact Phone',
        ],
        'products' => [
            'add' => 'Add New Product',
            'edit' => 'Edit Product',
            'created' => 'Product Created Successfully',
            'updated' => 'Product Updated Successfully',
            'deleted' => 'Product Deleted Successfully',
            'delete_message' => 'Are you sure you want to delete this product?',
            'selected_delete_message' => 'Are you sure you want to delete selected products?',
            'price' => 'Price',
            'stock_quantity' => 'Stock Quantity',
            'initial_stock_quantity' => 'Initial Stock Quantity',
            'category' => 'Category',
        ],
    ];

    public static $eStoreTranslations = [];

    public static function getTranslationArray($moduleName)
    {
        if ($moduleName == 'Estore') {
            return self::$eStoreTranslations;
        } else if ($moduleName == 'Superadmin') {
            return \App\SuperAdmin\Classes\SuperAdminLangTrans::$mainTranslations;
        }

        return self::$mainTranslations;
    }

    public static function seedTranslations($moduleName = '')
    {
        $allLangs = Lang::all();

        $translations = self::getTranslationArray($moduleName);

        foreach ($translations as $group => $translation) {
            foreach ($translation as $transKey => $transValue) {

                foreach ($allLangs as $allLang) {
                    $translationCount = Translation::where('lang_id', $allLang->id)
                        ->where('group', $group)
                        ->where('key', $transKey)
                        ->count();

                    if ($translationCount == 0) {
                        $newTranslation = new Translation();
                        $newTranslation->lang_id = $allLang->id;
                        $newTranslation->group = $group;
                        $newTranslation->key = $transKey;
                        $newTranslation->value = $transValue;
                        $newTranslation->save();
                    }
                }
            }
        }
    }

    public static function seedMainTranslations()
    {
        // Main Module
        self::seedTranslations();

        // Seeding modules
        self::seedAllModulesTranslations();
    }

    public static function seedAllModulesTranslations()
    {
        $allModules = Module::all();
        foreach ($allModules as $allModule) {
            self::seedTranslations($allModule);
        }
    }
}
