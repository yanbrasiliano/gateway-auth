# Gateway Auth with LDAP Record 

This is a simple example of how to use the `gateway-auth` module to authenticate users against an LDAP server.

## Test LDAP With LDAPWhoAmi

```bash
ldapwhoami -H ldap://10.65.101.200 -D "CN=teste,OU=Users,DC=uefs,DC=br" -x -W
```
After running the command, you will be prompted to enter the password for the user `CN=teste,OU=Users,DC=uefs,DC=br`. If the password is correct, you will see the following output:

```bash
dn:CN=teste,OU=Users,DC=uefs,DC=br
```

## Generating API Documentation

To generate the Swagger API documentation for your project, you can run the following artisan command:

```bash
php artisan l5-swagger:generate
```

### Acessing the API Documentation

Once the API documentation is generated, you can view it by visiting the following URL in your web browser:

localhost:8001/api/documentation#/
