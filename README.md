Web app boilerplate
===

Using Quasar, Symfony 5.2.

It's been a while since I touched any programming language.

Make sure to read the README in each subdir.

I included certs for a local `https://hotspotdev.me` "hosts" domain.

Includes a Login with Google. Login with Apple in the future?

Backend also includes some logic for basic entities such as addresses and preferences.

It's a bummer, but HTTPS is turned OFF to avoid multiple errors on mobile development.

It's getting tougher for me to get this running:
* I run my Quasar dev server with the default hostname, but I modify the server in assets/capacitor.config.json to hotspotdev.me:
    * reason: My google client has redirect_uri to `hotspotdev.me`. No way I put a local IP in there.
    * Why don't I put hotspotdev.me as the domain in quasar conf? For some reason I can't get it to work, threads and issues already opened
    * If I use HTTPS, capacitor plugins are not enabled (local CA stuff)

* I run my Symfony without HTTPS because my capacitors use HTTP, and you know how CORS work
* don't forget to start the postgres container

Works for:
* [x] PWA
* [x] Android (capacitor)
* [ ] iOS (capacitor) (not tomorrow, I don't have an iPhone)

Quick starter pack:
* `$ quasar dev -m pwa`
* `$ docker run --name=my_postgres --env=POSTGRES_PASSWORD=testapp123 --volume=my_dbdata:/var/lib/postgresql/data -p 54320:5432 postgres`
* `$ symfony server:start --p12=../certs/Cert.p12`

I've made a simple start script for this, but please read it first, you'll need `tmux` and modify the command name first.
Usage: `./start # for web` or `./start cap` for android capacitor. `./start stop` to stop the docker database.

<3 to symfony & quasar teams for the cool frameworks!

I use this repo for myself but if anyone wants to use it or whatever please feel free 
to ask me anything.

### Setup for dummies

In client:
* make .env.local
* make src-capacitor/capacitor.config.json
* make sure to follow the installation steps for capacitor modules (eg @codetrix-studio/googleauthloginthingy)

In server: 
* make .env.local


