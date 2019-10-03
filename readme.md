# Encrypting database columns with an ORM

This project is a short example of using an ORM to transparently encrypt database columns by using hooks on the model.

## Running the code

To run the project, use the following commands

    cd docker
    docker-compose up -d
    docker exec -it php /bin/bash
    composer install
    php index.php
   
## Example output

The first line of the output shows the raw string that is stored in the database.

    Raw email address for user [1] is [MUIEAERURJO1tJWAFB0tjalh-VZ7jfNnKM9nax6d49-NdM7rDGAMURujowu3UDnH6Ph2x8v4z0Odb_Dp_-0SSGpryMsefs9nvwFMiPQjL3fqMUKpAQm-h1-F4qWnMqFlBEFF0g97dq5SLYXLXyiZ_oMp_YBXCfoo4wUnFCznic2r5JV-hSmeLCBA5J8=]
    When fetched from ORM the value is [demo@example.com]
    
If an attacker is able to read the database by exploiting a vulnerability then they will
not easily be able to retrieve the values.  They'll need to either exploit your application or
read your encryption key from disk.See also [envelope encryption](https://github.com/andybeak/envelope-encryption) as an example of using a vault to avoid deploying your encryption key with your source code.
    