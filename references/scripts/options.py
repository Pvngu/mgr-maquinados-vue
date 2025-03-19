import os
import pyperclip
import pyautogui
import time
import itertools
import threading
import sys
import inquirer

def clear():
    os.system('cls' if os.name=='nt' else 'clear')

def loading_animation():
    global loading
    for c in itertools.cycle(['|', '/', '-', '\\']):
        if not loading:
            break
        sys.stdout.write('\rExecuting prompt ' + c)
        sys.stdout.flush()
        time.sleep(0.1)
    sys.stdout.write('\rDone!               \n')

def to_pascal_case(name): 
    return ''.join(word.capitalize() for word in name.split())

def generate_fullstack():
    clear()
    print("Generate Fullstack Components 📦")
    print("-" * 30)

    input("Well get started on the backend side first. 🤓☝️")

    data = generate_backend(is_fullstack=1)

    input("\nNow wait for the backend to be done and hit Enter. 🕒")

    input("\nGreat!, let's move on to the frontend side. 🚀")

    generate_view(data)

def generate_view(data=None):
    clear()
    print("Generate Frontend Components 🖥️")
    print("-" * 30)
    
    if data is None:
        name = input("Enter Component name:\n").strip()
        columns = input("Enter Columns separated by commas:\n").strip().lower()

    if data is not None:
        questions = [
            inquirer.Checkbox(
                'selected_columns',
                message="Select the columns you want to display in the table",
                choices=data['columns'].split(','),
            ),
        ]
        answers = inquirer.prompt(questions)
        selected_columns = answers['selected_columns']
        selected_columns_str = ', '.join(selected_columns)
        columns = selected_columns_str

    add_tabs = input("Do you want to add tabs filter? 📑 (y/n):\n").strip().lower()
    tabs = ""
    if add_tabs == 'y':
        questions = [
            inquirer.List(
            'selected_tabs_preferences',
            message="Select tabs preferences",
            choices=['Custom', 'Select Column'],
            ),
        ]
        answers = inquirer.prompt(questions)
        selected_tabs_preferences = answers['selected_tabs_preferences']
        if selected_tabs_preferences == 'Custom':
            tabs = input("Enter tabs separated by commas:\n").strip().lower()
        else:
            questions = [
                inquirer.List(
                    'selected_tab',
                    message="Select the tab",
                    choices=columns.split(','),
                ),
            ]
            answers = inquirer.prompt(questions)
            selected_tab = answers['selected_tab']
            tabs = selected_tab

    if data is None:
        add_filters = input("Do you want to add filters? 🌪️ (y/n):\n").strip().lower()
        filters = ""
        if add_filters == 'y':
            questions = [
                inquirer.Checkbox(
                    'selected_columns',
                    message="Select the columns you want to display in the table",
                    choices=columns.split(','),
                ),
            ]
            answers = inquirer.prompt(questions)
            selected_columns = answers['selected_columns']
            selected_columns_str = ', '.join(selected_columns)
            filters = selected_columns_str

        extraFilters = ""
        include_dates = 0
        add_dates = input("Do you want to add a filter for dates? 📅 (y/n):\n").strip().lower()
        if add_dates == 'y':
            include_dates = 1

        add_extraFilters = input("Do you want to add extra filters? ➕🌪️ (y/n):\n").strip().lower()
        if add_extraFilters == 'y':
            extraFilters = input("Enter extra filters separated by commas:\n").strip().lower()

        search = ""
        search_text = ""
        add_search = input("Do you want to add a search filter? 🔍 (y/n):\n").strip().lower()  
        if add_search == 'y':
            questions = [
                inquirer.Checkbox(
                    'selected_search_filters',
                    message="Select the filters",
                    choices=columns.split(','),
                ),
            ]
            answers = inquirer.prompt(questions)
            selected_search_filters = answers['selected_search_filters']
            selected_search_filters_str = ', '.join(selected_search_filters)
            search = selected_search_filters_str
    else:
        name = data['name']
        filters = data['filters']
        search = data['search']
        extraFilters = data['extraFilters']
        include_dates = data['include_dates']
    
    if(filters or extraFilters or include_dates or search):
        filters_text = (
            f"Filters: {filters if filters + ',' else ''} {extraFilters if extraFilters + ',' else ''} {search}" if filters or extraFilters or search else "Remove Filters component"
        ) + (
            "\nRemove the dates filters from the filters component." if include_dates == 0 else ""
        )
    else:
        filters_text = "Remove 'template #title' element from a-table component"
        
    if ',' not in search:
        search_text = "Remove all elements inside a-input-group but leaving a-input-search element and remove style from input"
    
    prompt = f"""Create a Vue.js component structure and specifications similar to the attached files and based on the provided template. Follow these guidelines:
1. **Name:** {name}”.
2. **Table Configuration:**
   - Columns: {columns}
   - {filters_text}
   - {search_text if search else "Remove a-col with a-input-search element"}
   - {tabs and f"Tabs: {tabs}" or "Remove Tabs component along with a-row parent element"}
3. **permsArray:**
   - Make sure to replace the name by {name}

Lastly do the following steps:
**Create Route**: Create the route file in resources/js/main/router/... (take users.js as reference) and once created it add it to resources/js/main/router/index.js 
Add the new menu to LeftSidebar.vue 
Add the new translations to LangTrans.php

MAKE SURE TO TO PLACE THE VIEWS IN THE CORRECT FOLDER IN resources/js/main/views/... AND INCLUDE Index.vue, AddEdit.vue and fields.js

**The folder of the attached files are only for reference purposes you must include the files to the corresponding folder in resources/js/main/... **
"""
    print("\nEnsure \033[91mrequired Views files, users.js, LangTrans, LeftSidebar\033[0m are attached. Once done, press ENTER to proceed.")

    pyperclip.copy(prompt)

    execute_prompt()

def generate_backend(is_fullstack=0):
    clear()
    print("Generate Backend Components 📦")
    print("-" * 30)
    
    name = input("Enter Model name:\n").strip()
    columns = input("Enter Migration columns separated by commas:\n").strip().lower()

    if columns == "":
        print("Hey I can't see the columns. 🤨")
        return

    add_filters = input("Do you want to add filters? 🌪️ (y/n):\n").strip().lower()
    filters = ""
    if add_filters == 'y':
        questions = [
            inquirer.Checkbox(
                'selected_filters',
                message="Select the columns you want to be filterable",
                choices=columns.split(','),
            ),
        ]
        answers = inquirer.prompt(questions)
        selected_filters = answers['selected_filters']
        selected_columns = ', '.join(selected_filters)

    extraFilters = ""
    include_dates = 0
    if is_fullstack == 1:
        add_dates = input("Do you want to add a filter for dates? 🌪️ (y/n):\n").strip().lower()
        if add_dates == 'y':
            include_dates = 1

        add_extraFilters = input("Do you want to add extra filters? ➕🌪️ (y/n):\n").strip().lower()
        if add_extraFilters == 'y':
            extraFilters = input("Enter extra filters separated by commas:\n").strip().lower()

        search = ""
        add_search = input("Do you want to add a search filter? 🔍 (y/n):\n").strip().lower()  
        if add_search == 'y':
            questions = [
                inquirer.Checkbox(
                    'selected_search_filters',
                    message="Select the filters",
                    choices=columns.split(','),
                ),
            ]
            answers = inquirer.prompt(questions)
            selected_search_filters = answers['selected_search_filters']
            selected_search_filters_str = ', '.join(selected_search_filters)
            search = selected_search_filters_str
    
    # Format the prompt
    prompt = f"""I want to create a new model for {to_pascal_case(name)} in my project, I want these files to be similar to the attached files. Here are the specific requirements:

Backend Components:

Model: Create a model and make the relations of the foreign keys (make sure to make it similar to Creditor.php and use extends BaseModel).
Controller: Create a controller for handling model-related actions.
Requests: Create form requests for validation.
Migration: Create a migration for the Models table.

The columns for the migration are:
{columns}
If there's a foreign key in the columns, make sure to add the relation in the model and if the migration has not been created add it.

{filters and extraFilters and f"I want to add only the id and the following filters (in $filterable array): {filters}, {extraFilters}" or ""}

{include_dates == 0 and "Remove the dates filters from modifyIndex in the controller." or ""}

Also, I want to create a route in web.php (this must be within the group where all resoruces are located) for the model just like the following example:
ApiRoute::resource('creditors', 'CreditorController', $options);

**The folder of the attached files are only for reference purposes you must include the files to the corresponding folder in laravel. Also remove all comments you find and make sure to follow instructions from the comments in the files.**"""
    
    pyperclip.copy(prompt)
    # print("Prompt copied to clipboard!\n")

    input("\nEnsure the necessary files are attached. Once done, press ENTER to proceed.")
    
    execute_prompt()

    if(is_fullstack == 1):
        return {
            'name': name,
            'filters': filters,
            'extraFilters': extraFilters,
            'include_dates': include_dates,
            'search': search,
            'columns': columns
        }

def execute_prompt():
    global loading

    # Start the loading animation in a separate thread
    loading = True
    t = threading.Thread(target=loading_animation)
    t.start()

    pyautogui.hotkey('tab')
    
    # open copilot
    pyautogui.hotkey('command', 'shift', 'i')

    # Select all text inside the textbox and delete it
    pyautogui.hotkey('command', 'a')
    pyautogui.press('delete')

    pyautogui.hotkey('command', 'v')

    pyautogui.hotkey('command', 'enter')

    loading = False