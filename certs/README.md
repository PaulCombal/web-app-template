Certs
===

Follow https://deliciousbrains.com/ssl-certificate-authority-for-local-https-development/

or https://stackoverflow.com/a/60516812

make sure to click the "Authorities" "tab" in chrome first when importing

to convert to p12 format for symfony dev server (no password):

`$ openssl pkcs12 -export -out Cert.p12 -in domain.me.crt -inkey domain.me.key`

as seen here https://github.com/symfony/cli/issues/393


If you use Quasar, don't forget to conf it too https://quasar.dev/quasar-cli/quasar-conf-js#Property%3A-devServer