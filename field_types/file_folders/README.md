# PyroStreams File Folders Field Type
Allows you to assign a files folder to a stream entry to use for images/files/etc.

## Installation
To install, download from GitHub and rename the folder to "file_folders". Put this in your addons/site_ref/field_types/ or addons/shared_addons/field_types folder. Once you've placed it into one of these folders, it'll be ready to use with PyroStreams.

## Usage
Will allow for a loop that will show each file
To display the files inside the folder assigned to the entry, you can run them in a cycle just like you would the main stream with the related function:

        {{ streams:cycle stream="portfolio" }}

        <h2>{{ name }}</h2>

            <ul>
            {{ files:listing folder="{{ file_folder.id }}" }}
                <li><img src="{{ path }}" alt="{{ description }}"/></li>
            {{ /files:listing }}
            </ul>

        {{ /streams:cycle }}
