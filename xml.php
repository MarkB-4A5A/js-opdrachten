<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>VictorScript</title>
    <style>
    table tr td th{
      border: 1px solid black;

    }

    table {border-collapse: collapse;}
    textarea {
        width: 500px;
        height: 300px;
    }
    </style>
  </head>
  <body>
      <input type="text" placeholder="Insert country ID"><button>Get XML</button><br>
      <textarea></textarea>
      <p></p>
      <table>
          <tr>
              <th>id</th>
              <th>name</th>
          </tr>
      </table>
  </body>
  <?php

    echo password_hash("")
  ?>
  <script>
        var input = document.querySelector("input");
        var button = document.querySelector("button");
        var textarea = document.querySelector("textarea");
        var paragraph = document.querySelector("p");
        var table = document.querySelector("table");

        button.addEventListener("click", function(){
            getResponse("http://localhost/victorscript/webservices/api-countries.php?output=xml&id="+input.value);
        });

        function getResponse(url) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET",url,false);
            xhr.send();

            textarea.innerHTML = xhr.responseText;
            parseXML(xhr.responseText);
        }

        function parseXML(response) {
            parser = new DOMParser();
            xmlDoc = parser.parseFromString(response,"text/xml");
            paragraph.innerHTML = "Country name: " + xmlDoc.getElementsByTagName("name")[0].childNodes[0].nodeValue;

            table.innerHTML += "<tr><td>" + xmlDoc.getElementsByTagName("id")[0].childNodes[0].nodeValue + "</td><td>"+xmlDoc.getElementsByTagName("name")[0].childNodes[0].nodeValue+"</td></tr>";

        }

  </script>

</html>
