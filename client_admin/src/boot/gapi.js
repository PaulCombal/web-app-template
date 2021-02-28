// import something here

// We're not using Capacitor, so we have to make a replacement for the Google auth plugin
window.gapiLoaded = new Promise((resolve, reject) => {
  const clientConfig = {
    client_id: document.getElementsByName('google-signin-client_id')[0].content
  };
  const head = document.getElementsByTagName('head')[0];
  const script = document.createElement('script');
  script.type = 'text/javascript';
  script.defer = true;
  script.async = true;
  script.onload = () => {
    gapi.load('auth2', {
      callback: () => gapi.auth2.init(clientConfig).then(resolve).catch(reject),
      onerorr: reject
    })
  }
  script.src = 'https://apis.google.com/js/platform.js';
  head.appendChild(script);
})


// more info on params: https://quasar.dev/quasar-cli/boot-files
export default (/* { app, router, Vue ... } */) => {

}
