Web app boilerplate
===

Using Quasar, Symfony 5.2.

It's been a while since I touched any programming language.

Make sure to read the README in each subdir.

I included certs for a local `https://hotspotdev.me` "hosts" domain.

Includes a Login with Google. Login with Apple & Microsoft in the future?

This repo aims to be a boilerplate for a profrssionnal website with a unified backend, and separate webapps for users, "moderators" or whatever you want it to be, and admins.
Backend also includes some logic for basic entities such as addresses and preferences, as well as basic role administration.

It's a bummer, but HTTPS is turned OFF to avoid multiple errors on mobile development.
I run my Symfony without HTTPS because my capacitors use HTTP, and you know how CORS work

I've made a simple start script for this, but please read it first, you'll need `tmux` and modify the command name first.
Usage: `./start # for web pwa` or `./start cap` for android capacitor. `./start stop` to stop the docker database.
Use `./start 3` to start all 3 frontends + backend.
Use `./start back` to start only the docker db and the symfony dev server.

Works for:
* [x] PWA
* [x] Android (capacitor)
* [ ] iOS (capacitor) (not tomorrow, I don't have an iPhone)

Quick starter pack:
* `$ quasar dev -m pwa`
* `$ docker run --name=my_postgres --env=POSTGRES_PASSWORD=testapp123 --volume=my_dbdata:/var/lib/postgresql/data -p 54320:5432 postgres`
* `$ symfony server:start --p12=../certs/Cert.p12`

There are 3 clients for one backend:
* user client
* moderator/curator client
* site admin client

❤️ to symfony & quasar teams for the cool frameworks!

I use this repo for myself but if anyone wants to use it or whatever please feel free 
to ask me anything.

Owner and admin frontend will only support pwa mode (no capacitor). Google auth is specific to these two modes.

### Setup for dummies

In client:
* make .env.local
* make src-capacitor/capacitor.config.json
* make sure to follow the installation steps for capacitor modules (eg @codetrix-studio/capacitor-google-auth)

In server: 
* make .env.local

### License

This work is under the DBAD license. Not the framework itselves, but the business logic & styles I implemented.
