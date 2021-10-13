class Utils {
  googleApi;
  /* urlbulder */
  "use strict";
  urlBuilder(params) {
    var builtUrl = params.base;

    builtUrl += "?";

    if (params.apiKey) {
      builtUrl += "key=" + params.apiKey + "&";
    }

    if (params.client) {
      builtUrl += "client=" + params.client + "&";
    }

    if (params.libraries.length > 0) {
      builtUrl += "libraries=";

      params.libraries.forEach(function (library, index) {
        builtUrl += library;

        if (index !== params.libraries.length - 1) {
          builtUrl += ",";
        }
      });

      builtUrl += "&";
    }

    if (params.language) {
      builtUrl += "language=" + params.language + "&";
    }

    if (params.version) {
      builtUrl += "v=" + params.version + "&";
    }

    builtUrl += "callback=" + params.callback;

    return builtUrl;
  }
  /* UrlBuilder */

  //var Promise = new Promise;

  loadAutoCompleteAPI(params) {
    var script = document.createElement("script");

    script.type = "text/javascript";

    script.src = urlBuilder({
      base: "https://maps.googleapis.com/maps/api/js",
      libraries: params.libraries || [],
      callback: "googleMapsAutoCompleteAPILoad",
      apiKey: params.apiKey,
      client: params.client,
      language: params.language,
      version: params.version,
    });

    document.querySelector("head").appendChild(script);
  }

  /**
   * googleMapsApiLoader
   *
   * @param  {object} params
   * @param  {object} params.libraries
   *
   * @return {promise}
   */
  googleMapsApiLoader(params) {
    if (googleApi) {
      return Promise.resolve(googleApi);
    }

    return new Promise(function (resolve, reject) {
      loadAutoCompleteAPI(params);

      window.googleMapsAutoCompleteAPILoad = function () {
        googleApi = window.google;
        resolve(googleApi);
      };

      setTimeout(function () {
        if (!window.google) {
          reject(new Error("Loading took too long"));
        }
      }, 5000);
    });
  }
}
