# API Gateway LDAP Authentication 

API Gateway project example for user authentication using LDAP at the University of the State of Bahia (UEFS).

## Configuring LDAP Credentials in the .env File

Before starting authentication, it is essential to configure the LDAP credentials in your environment configuration file (`.env`). Make sure you provide the correct LDAP server information, including the server address, port, base DN (Distinguished Name) for searches, user and password, as required for connection and authentication to the LDAP server.

Example of settings in the `.env` file:

```bash
LDAP_HOSTS=LDAP_SERVER_HOST
LDAP_BASE_DN=DN_BASE_FOR_LDAP_SEARCH
LDAP_USER=CN_USER_LDAP
LDAP_PASSWORD=LDAP_USER_PASSWORD
```

## Testing LDAP with LDAPWhoAmi

```bash
ldapwhoami -H ldap://10.65.101.200 -D "CN=test,OU=Users,DC=uefs,DC=br" -x -W
```
After running the command, you will be asked to enter the password for the user `CN=test,OU=Users,DC=uefs,DC=br`.  If the password is correct, you will see the following output:

```bash
dn:CN=test,OU=Users,DC=uefs,DC=br
```

## Generating API documentation

To generate the Swagger API documentation for your project, you can run the following artisan command:

```bash
php artisan l5-swagger:generate
```

### Accessing the API Documentation

Once the API documentation has been generated, you can view it by visiting the following URL in your web browser locally:

```bash
localhost:8001/api/documentation#/
```

