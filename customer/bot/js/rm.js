var base_url = "http://localhost/ems/customer/";
var chatWindow = new Bubbles(
    document.getElementById("chat"),
    "chatWindow",
    {
      // the one that we care about is inputCallbackFn()
      // this function returns an object with some data that we can process from user input
      // and understand the context of it

      // this is an example function that matches the text user typed to one of the answer bubbles
      inputCallbackFn: function (chatObject) {
        var miss = function (text_inp = null) {
          var xhr = new XMLHttpRequest();
          var url = base_url + "api.php";

          var input = false;
          if (text_inp) {
            input = text_inp;
          } else {
            input = chatObject.input;
          }

          // RASA's POST format
          var request_body = {
            message: input
          };

          xhr.onreadystatechange = function () {
            if (xhr.readyState == XMLHttpRequest.DONE) {
            console.log(xhr.response);
              response = JSON.parse(xhr.responseText);

              var answers = [];
              var re = [];

              for (i = 0; i < response.length; i++) {
                    answers.push(response[i]["text"]);
              }

              chatWindow.talk(
                {
                  talk: {
                    says: answers,
                    reply: re,
                  },
                },
                "talk"
              );
            }
          };

          xhr.open("POST", url, true);
          xhr.setRequestHeader("Content-Type", "application/json");
          xhr.send(JSON.stringify(request_body));
        };

        var found = false;
        if (chatObject.e) {
          chatObject.convo[chatObject.standingAnswer].reply.forEach(
            function (e, i) {
              strip(e.question).incldues(strip(chatObject.input)) &&
              chatObject.input.length > 0
                ? (found = e.answer)
                : found
                ? null
                : (found = false);
            }
          );
        } else {
          found = false;
        }

        miss(found);
      },

      //This function is called when the user clicks on a bubble button option. This callback is useful for the tasks that require dynamic handling of input rather than a static approach(like NLC).
      responseCallbackFn: function (chatObject, key) {
        var xhr = new XMLHttpRequest();
        var url = base_url + "api.php";

        var input = key;
       
        xhr.onreadystatechange = function () {
          if (xhr.readyState == XMLHttpRequest.DONE) {
            response = JSON.parse(xhr.responseText);

            var answers = [];
            var re = [];
            for (i = 0; i < response.length; i++) {
                  answers.push(response[i]["text"]);
                
            }

            chatWindow.talk(
              {
                talk: {
                  says: answers,
                  reply: re,
                },
              },
              "talk"
            );
          }
        };

        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.send(JSON.stringify(request_body));
      },
    }
  );

  var convo = {
    ice: {
      says: ["Hi"],
    },
  };

  chatWindow.talk(convo);