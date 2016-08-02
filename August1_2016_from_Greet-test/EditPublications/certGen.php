<?php
//Should not happen since this should be in a directory that does not ask for client certificates
if($_SERVER['SSL_CLIENT_S_DN_CN'])
	die("You are already authenticated as ".$_SERVER["SSL_CLIENT_S_DN_CN"]);
date_default_timezone_set('UTC');
$CAorg = 'MyApp';
$CAcountry = 'US';
$CAstate = 'CA';
$CAcity = 'Sacramento';
$confpath = '/var/www/auth/ca/ca.conf';
$cadb = '/var/www/auth/ca/index.txt'; //will need to be reset
$days = 3650;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	echo $cadb;
  $f = fopen($cadb, 'w'); //reset CA DB
  fclose($f);
  $uniqpath = tempnam('/tmp/','certreq');
  $username = $_POST['username']; //Validate this first!
  $CAmail = "qzhang@anl.gov"; //This too! Make sure that's their email.
//If they're submitting a key, first save it to an spkac file
  $key = $_POST['pubkey'];
  if (preg_match('/\s/',$username) || preg_match('/\s/',$CAmail))
    die("Must not have whitespace in username or email!");
  $keyreq = "SPKAC=".str_replace(str_split(" \t\n\r\0\x0B"), '', $key);
  $keyreq .= "\nCN=".$username;
  $keyreq .= "\nemailAddress=".$CAmail;
  $keyreq .= "\n0.OU=".$CAorg." client certificate";
  $keyreq .= "\norganizationName=".$CAorg;
  $keyreq .= "\ncountryName=".$CAcountry;
  $keyreq .= "\nstateOrProvinceName=".$CAstate;
  $keyreq .= "\nlocalityName=".$CAcity;
  echo $keyreq;//qz
  file_put_contents($uniqpath.".spkac",$keyreq);
//Now sign the file 
  $command = "openssl ca -config ".$confpath." -days ".$days." -notext -batch -spkac ".$uniqpath.".spkac -out ".$uniqpath.".out 2>&1";
  $output = shell_exec($command);
//And send it back to the user
  $length = filesize($uniqpath);
  header('Last-Modified: '.date('r+b'));
  header('Accept-Ranges: bytes');
  header('Content-Length: '.$length);
  header('Content-Type: application/x-x509-user-cert');
  readfile($uniqpath.".out");
  unlink($uniqpath.".out");
  unlink($uniqpath.".spkac");
  unlink($uniqpath);
  exit;
}
?>
<!DOCTYPE html>
<html>
<meta charset="utf-8">
<title>Keygen sample</title>
<body>
    <article>
        <header>
            <h1>Let's generate you a cert so you don't have to use a password!</h1>
			<p>Hit the Generate button and then install the certificate it gives you in your browser.
				All modern browsers (except for Internet Explorer) should be compatible.</p>
            <p>Demo showing keygen markup element</p>
        </header>
        <footer>
            <h1></h1>
            <p><strong>Note:</strong> The keygen tag is not supported in Internet Explorer.</p>
			<p><strong>Wait a minute, then refresh this page over HTTPS to see your new cert in action!</strong></p>
        </footer>
    </article>
    <form method="post">
        The username I want: <input type="text" name="phrase">
        Encryption: <keygen name="security">
        <input type="submit" name="createcert" value="Generate">
    </form>
</body>
</html>
<?php
/************
Here are the steps:

0. I started by installing Ubuntu Server, selecting the LAMP option, and otherwise using defaults. Your server may have slightly different configuration file paths. Switch to root since apache configuration requires root privileges.

sudo bash
1. Create a root CA for your application

mkdir /etc/apache2/ssl.crt/
cd /etc/apache2/ssl.crt/
openssl genrsa -out rootCA.key 2048
openssl req -x509 -new -nodes -key rootCA.key -days 7300 -out rootCA.pem
Fill in appropriate values as prompted.

cp rootCA.pem ca-bundle.crt
2. Enable SSL on Apache

cd /etc/apache2/sites-enabled/
ln -s ../sites-available/default-ssl.conf
a2enmod ssl
If you purchased a cert, you could install that now.
Then edit /etc/apache2/sites-available/default-ssl.conf with your favorite editor and uncomment the line "SSLCACertificateFile /etc/apache2/ssl.crt/ca-bundle.crt" which tells the web server to respect your CA.

sed -i.bak 's/#SSLCACertificateFile/SSLCACertificateFile/' /etc/apache2/sites-available/default-ssl.conf
Make sure there is a line "SSLOptions +StdEnvVars" (should be there by default, add if necessary)
And since we also want to allow use of .htaccess files, (although you could put all the directives in the apache conf files instead of .htaccess)

sed -i.bak 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf
service apache2 restart
Now you can go visit https://1.2.3.4/ or whatever your server's IP is to verify it works

3. Set up client auth on a directory

mkdir /var/www/auth
cd /var/www/auth
echo '<?php phpinfo();' > index.php
echo SSLVerifyClient optional > .htaccess
echo SSLVerifyDepth 1 >> .htaccess
echo SSLOptions +ExportCertData +StdEnvVars >> .htaccess
Now go visit https://1.2.3.4/auth or whatever your server's IP is, and in the "Apache Environment" section you should see SSL_CLIENT_VERIFY None

4. Create an openssl CA configuration file and CA directory. To keep our web app more self-contained, we'll create this as an inaccessible subdirectory of it.
4.1 Create the directory

mkdir /var/www/auth/ca/
cd /var/www/auth/ca/
touch index.txt
mkdir newcerts
echo 1000 > serial
echo Deny from all > .htaccess
chown -R www-data .

4.2 Save this file as /var/www/auth/ca/ca.conf

[ ca ]
default_ca      = CA_default
[ CA_default ]
dir            = /var/www/auth/ca/
database       = $dir/index.txt
new_certs_dir  = $dir/newcerts
certificate    = /etc/apache2/ssl.crt/rootCA.pem
serial         = $dir/serial
private_key    = /etc/apache2/ssl.crt/rootCA.key
RANDFILE       = $dir/private/.rand
default_days   = 3650
default_crl_days= 60
default_md     = sha1
policy         = policy_any
email_in_dn    = yes
name_opt       = ca_default
cert_opt       = ca_default
copy_extensions = none
[ policy_any ]
countryName            = supplied
stateOrProvinceName    = optional
organizationName       = optional
organizationalUnitName = optional
commonName             = supplied
emailAddress           = optional
5. Create a certificate generation page. It must display a keygen form, receive submitted certificate requests, then generate and send the client certificate back. Save this example page as /var/www/certGen.php:
*************/


/*********************88888888888
How to use client certificates with apache

This How-To is intended to give a step-by-step configuration guide for creating your own certificate authority (CA) and how to configure apache to allow only certain self-signed user certificates to connect to the webserver. Any other browser connects will be discarded. So let's start with the requirements.

Requirements:

administrator with UNIX experience
apache webserver 1.3.26 or greater
mod-ssl 2.8.10-1.3.26 or greater, built as DSO for apache
openssl 0.9.6 version g or greater
Let's assume that the needed software components are located in following installation directories:

apache-1.3.26 -> /opt/apache-1.3.26-ssl
openssl-0.9.6g -> /opt/openssl-0.9.6
1. Create Certificate Authority

While doing this always keep in mind that the root CA certificate you will create now is used by the webserver as comparison to the final webserver certificate and that this root CA certificate must be imported in your browser. For doing this without any oncoming problems we advise to create RSA keys without a passphrase; otherwise apache and your browser will not be able to resolve the certificate chain!

a) openssl configuration

All the data you enter here is really important and should be rememberd as you entered it. Therefore I recommend to modify openssl's configuration so you don't have to keep it always in mind. The data are needed later for the client's certificates.

Let's try it by an example (openssl.cnf):

...
[ ca ]
default_ca      = garexCA                # The default ca section
...
[ garexCA ]

dir             = /opt/openssl-0.9.6/garexCA  # Where everything is kept
certs           = $dir/certs            # Where the issued certs are kept
crl_dir         = $dir/crl              # Where the issued crl are kept
database        = $dir/index.txt        # database index file.
new_certs_dir   = $dir/newcerts         # default place for new certs.

certificate     = $dir/CA/garexCA.CRT    # The CA certificate
serial          = $dir/serial           # The current serial number
crl             = $dir/CA/garexCA.CRL # The current CRL
private_key     = $dir/CA/garexCA.KEY # The private key

# Choose the prngd socket for a better random seed or if you don't
# have a random device, e.g. /dev/random or /dev/urandom
# prngd of course has to be compiled first :)
#RANDFILE       = $dir/private/.rand    # private random number file
RANDFILE        = /var/run/prngd-socket # private random number file
...

[ req_distinguished_name ]
countryName                     = Country Name (2 letter code)
countryName_default             = DE # MUST be in capitals
countryName_min                 = 2
countryName_max                 = 2

stateOrProvinceName             = State or Province Name (full name)
stateOrProvinceName_default     = Bavaria

localityName                    = Locality, e.g. Hempels Couch
localityName_default            = Munich

0.organizationName              = Organization Name
0.organizationName_default      = garex AG

# we can do this but it is not needed normally :-)
#1.organizationName             = Second Organization Name (eg, company)
#1.organizationName_default     = World Wide Web Pty Ltd

organizationalUnitName          = Organizational Unit
organizationalUnitName_default  = Administration

commonName                      = Common Name (eg, YOUR name)
commonName_max                  = 64

emailAddress                    = Email Adress
emailAddress_default            = support@garex.net
emailAddress_max                = 40
b) Creating the CA

First, let us change the directory to /opt/openssl-0.9.6

# cd /opt/openssl-0.9.6
Create a CA subdirectory in /opt/openssl-0.9.6
# mkdir -p ./garexCA/CA
Create CA key with 1024 bit
# ./bin/openssl genrsa -out ./garexCA/CA/garexCA.KEY
Create Certificate Request
# ./bin/openssl req -new -key ./garexCA/CA/garexCA.KEY -out \
./garexCA/CA/garexCA.CSR
Self-sign certificate
# ./bin/openssl x509 -req -days 365 -in ./garexCA/CA/garexCA.CSR \
-out ./garexCA/CA/garexCA.CRT -signkey /opt/openssl-0.9.6/garexCA/CA/garexCA.key
Now we finally have our CA certificate and our CA key, which we will use to sign our webserver and our client certificates. To check the certificates content you can openssl tell to print it in text:

# openssl x509 -in garexCA.CRT -text
2. Creating the webserver certificate

This is quite straightforward like we had it above with the garex CA. But this time we won't use the same key for signing but the garex CA Key. A good idea would be to sort out the keys, the certificate requests and the final certificates. This can be done by creating the following directory structure:

/opt/openssl-0.9.6/garexCA/server
/opt/openssl-0.9.6/garexCA/server/certificates
/opt/openssl-0.9.6/garexCA/server/requests
/opt/openssl-0.9.6/garexCA/server/keys
/opt/openssl-0.9.6/garexCA/user
/opt/openssl-0.9.6/garexCA/user/certificates
/opt/openssl-0.9.6/garexCA/user/requests
/opt/openssl-0.9.6/garexCA/user/keys
a) Generating the webservers key

Again we first go to openssl's home directory:

# cd /opt/openssl-0.9.6/garexCA/server/keys
Then create the key by
# openssl genrsa -des3 -out garexWEB.KEY
The "-des3" option tell openssl to encrypt the key with a 3DES (Triple-DES) Passphrase. For later, this will be the startup passphrase for our webserver certificate. Always keep the passphrase in mind or save it to a file and pgp encrypt it later!!!
b) Generating the webservers certificate request

Now go to the server request directory and create the server certificate request:

# cd /opt/openssl-0.9.6/garexCA/server/requests
# openssl req -new -key /opt/openssl-0.9.6/garexCA/server/keys/garexWEB.KEY \
-out garexWEB.CSR
IMPORTANT NOTE: During the creation process you will be asked several questions, e.g. the "Common Name" (=CN). As the CN has no default value in openssl.cnf you MUST enter the complete webserver's name (FQDN=Fully Qualified Domain Name), e.g. hidden.garex.net!!! This string will be later compared by apache to the config directive "ServerName". If these strings are not identical, the webserver will not be able to start up!

c) Sign the webservers certificate request with the CA key

Go to the server directory:

# cd /opt/openssl-0.9.6/garexCA/server
# openssl ca -in requests/garexWEB.CSR -cert ../CA/garexCA.CRT -keyfile \
../CA/garexCA.KEY -out certificates/garexWEB.CRT
Again, we check the generated certificate with

# openssl x509 -in certificates/garexWEB.CRT -text
if everything is ok. The only difference to the CA certificate should be the common name entry, e.g. CN=garex CA != CN=hidden.garex.net. 
Now we're going to put the following three files in one tar-file: (zip will do also)

/opt/openssl-0.9.6/garexCA/server/certificates/garexWEB.CRT
/opt/openssl-0.9.6/garexCA/server/keys/garexWEB.KEY
/opt/openssl-0.9.6/garexCA/CA/garexCA.CRT
and put this tar file to our Webserver, where you extract it in the appropriate places.

3. Configuring apache

Now we have to edit the "httpd.conf". But first we better create a special subdirectory where the webserver key, the CA certificate and the webserver certificate will be placed in. Let's say we name it "certs". Now you extract the former created tar or zip archive in it:

# cd /opt/apache-1.3.26-ssl ; mkdir certs
# cd certs ; gtar xvfz TARFILE
# ls
CA	server
Now edit the httpd.conf and change the following configuration options:

ServerName hidden.garex.net
SSLEngine on
SSLCertificateFile /opt/apache-1.3.26-ssl/certs/server/certificates/garexWEB.CRT
SSLCertificateKeyFile /opt/apache-1.3.26-ssl/certs/server/keys/garexWEB.KEY
SSLCACertificateFile /opt/apache-1.3.26-ssl/certs/CA/garexCA.CRT
Just for convenience for not always being asked for the passphrase during apache's start phase we create a small and simple shellscript called "pp" (=pass phrase) and put it to /opt/apache-1.3.26-ssl/bin/pp. For instance, this would look like this one:

#!/bin/sh

case "$1" in
        hidden.garex.net*)
                echo "pw4support"
                ;;
esac
Now change the directive

SSLPassPhraseDialog  builtin
to

SSLPassPhraseDialog  exec:/opt/apache-1.3.26-ssl/bin/pp
I will definitely not explain how to get apache listening on port 443, which is the default https port :). Just take a look at "Listen". After saving, you should now be able to start the webserver by

# /opt/apache-1.3.26-ssl/bin/apachectl startssl
4. Creating the client certificates

Back to openssl usage. This is quite similar to the creation of the webserver's certificate:

# cd /opt/openssl-0.9.6/garexCA ; mkdir -p usercerts/garex
# cd usercerts/garex
a) Generate user key

 # openssl genrsa -des3 -out garex.key 1024
(Keep the passphrase in mind!)

b) Create user certificate request

 # openssl req -new -key garex.KEY -out garex.CSR
This time we enter for the common name (CN) our full name, e.g. "Martin Allert", and for the organizational unit "Fun dept.".

c) Sign user certificate request and create certificate

 # openssl ca -in garex.CSR -cert ../../CA/garexCA.CRT -keyfile \
../../CA/garexCA.KEY -out garex.CRT
Check again, if everything is ok

# openssl x509 -in garex.CRT -text
and if the certificate string "/C=DE..." is quite identical with the ones from above.

BE CAREFUL, THERE'S A TRAP:
Most common webbrowsers like Mozilla or Netscape can't cope with this certificate type. In the former sections we created certificates in PEM format. But those browsers need the certificate to be in another type, like PKCS#12. Therefore we do a conversion:

d) Convert user certificate and import it in your browser

 # openssl pkcs12 -export -clcerts -in garex.CRT -inkey garex.KEY -out garex.P12
During the conversion dialog you will be asked for an export password; enter anything you can remember, but don't let it be empty. What you get now is a file which not only keeps the certificate, but also your private Key. Copy this file to your workstation (Windows/Linux/Mac OS X), start Mozilla and go through the browsers menu structure like

Preferences -> Privacy & Security -> Manage Certificates -> Your Certificates ->
Import -> Choose file
Now enter your formerly chosen export password, then the passphrase of your previously generated private key, which is contained in the P12 file. Finished! But still there's a catch: the browser does not know anything about the CA which created and signed your new user certificate. To complete this task we have to import the root CA certificate as well. This is very easy, although it took me 2h to find out how to do with Mozilla :). Just put the garexCA.CRT on a public http port 80 webserver, enter the URL in your browser and click on the garexCA.CRT.

http://www.garex.net/garexCA.CRT
and - what a surprise - the browser recognizes this certifiacte as a new root CA certificate and offers you to import this certificate to your root CA chain. :))

Internet Explorer, the thing from a different world
Once again Microsoft's Internet Explorer has its own standards: it only accepts certificates of the type DER. Therefore we have to convert our user certificate and the root CA certificate:

# openssl x509 -inform PEM -in garex.CRT -outform DER -out garex.CRT.der
# openssl x509 -inform PEM -in garexCA.CRT -outform DER -out garexCA.CRT.der
Import these two certificates via IE and you are finished.

5. Reconfiguration of apache

Ok, we got an installed webserver certificate and we got an imported user certificate. But how can we force apache to not accept any certificate a user browser might come with? At this moment, our apache accepts any SSL connection. Therefore we have to force him to crosscheck whether the presented user certificate is valid, allowed to connect and if a username/password combination was verified successfully. For this, we change the following apache configuration options:

SSLVerifyClient require
SSLVerifyDepth  2
Usually, apache would verify the whole certificate chain up and down, e.g. if you bought a commercial certificate at VeriSign it would verify the signer of this VeriSign certificate, then the signer of VeriSign's root certificate, then the signer's signed certificate and so on. You see, it's a chain, a so called "web of trust". Apache's default value is 10, so in terms 10 root CA in the chain. Here the depth level of 2 is enough, as we only have one CA and one webserver/user certificate.

OK folks, but now we want only certain certificates to be allowed to connect. How can we manage this? Very easy, as we are now going to modify settings concerning the document root of our small SSL webserver. After the "DocumentRoot" directive we add:

...
<Directory "/www/hidden/docs">
<IfDefine SSL>
    SSLRequireSSL
    SSLRequire           %{SSL_CLIENT_S_DN_O}  eq "garex AG" and  
    %{SSL_CLIENT_S_DN_OU} in {"Fun dept."}
</IfDefine>
...
</Directory>
...
You see, if the "Organisation" we defined earlier in the openssl.cnf does not match the webserver certificate AND the user certificate AND the root CA certificate, EVERY request to this webserver will be dropped!!!

Effectively, the certificate content - the certificate string hidden within your certificate - will be checked; in this case we broke the check down to several components like organisation and organizational unit. Therefore it is a MUST, that the client certificate is verified against these two conditions, e.g. organisation equals to "garex AG" and organizational unit to "Fun dept." If you want to add other departments, just add them at the end of the verification line.

But we are not finished yet. Certificates are a nice thing, but what if the person leaves the room for a short time and a "bad guy" sits in front of the unlocked PC? So we add an "AuthConfig" line after the upper "</IfDefine>":

    AllowOverride AuthConfig
    Order deny,allow
    Allow from all

    AuthType Basic
    AuthName "Authentication required"
    AuthUserFile /opt/apache-1.3.26-ssl/etc/passwd
    AuthGroupFile /opt/apache-1.3.26-ssl/etc/group
    require valid-user
    require group support
    Satisfy all
Last but not least we create a passwd file and a group file:

# mkdir /opt/apache-1.3.26-ssl/etc
# /opt/apache-1.3.26-ssl/bin/htpasswd -c \
  /opt/apache-1.3.26-ssl/etc/passwd garex
The group file looks like this:

support: garex
NOW we have to restart apache for the new settings. You have to do a hard stop/start, as changes to the SSL configuration will otherwise not be loaded, as mod_ssl is already loaded by the httpd. And NOW you will see, that only an user with the proper certificate and username/password can get access to this webserver's content! Of course what is missing are so-called "Certificate Revocation Lists", which allows us to disable a user certificate with openssl, export this list into a file, load this file into apache and - voila! - the former valid user certificate is invalid.

6. Final security improvements

For increasing security some of the following suggestions may be applicable to your environment:

Instead of allowing all IP adresses for connections, change "Allow from all" to "Allow from 192.168.2.50" or "Allow from garex.net,arago.de". Advantage: Users who change to another PC are not automatically allowed to connect.
Create certificate revocation lists.
Setup a secure ldap server with a own certificate as described above at the webservers section, signed by your CA. Then authentication is done by crypted ldap queries, where the certificate string AND username/password is stored at a user invisible place.
Change your webapplication to use e.g. PHP, where every php command crosschecks for authentication to the ldap server and - based on the answer - only presents pages with links the user is allowed to see. An other user may have other privileges and may have more links to click on the same page.

**********************88888888888*/
?>