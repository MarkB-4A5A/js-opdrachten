<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>VictorScript</title>
    <style>

        input {
            width: 225px;
        }

        textarea {
            width: 300px;
            height: 240px;
        }

        table, th, tr, td{
          border: 1px solid black;
          border-collapse: collapse;
        }
    </style>
  </head>
  <body>
      <input type="number" placeholder="Amount of employees"><button>Get JSON</button><br>
      <textarea></textarea>
      <p></p>
      <table></table>

  </body>


  <script>

      var input = document.querySelector("input");
      var button = document.querySelector("button");
      var textarea = document.querySelector("textarea");
      var paragraph = document.querySelector("p");
      var table = document.querySelector("table");

      button.addEventListener("click", function(){
          getResponse("http://localhost/victorscript/webservices/api-employees.php?output=json&amount="+input.value);
      });

      function getResponse(url) {
          var xhr = new XMLHttpRequest();
          xhr.open("GET",url,false);
          xhr.send();

          textarea.innerHTML = xhr.responseText;

          showResponse(xhr.responseText);
      }

      function showResponse(response) {
          var object = JSON.parse(response);
          paragraph.innerHTML = "<b>The first company of the JSON object is:</b> " + object.employees[0].company;

          table.innerHTML = "<tr><th>Name</th><th>Company</th></tr>";
          for(var i=0;i<object.employees.length;i++) {
              table.innerHTML += "<tr><td>"+object.employees[i]["name"]+"</td>" + "<td>" + object.employees[i]["company"] + "</td></tr>";
          }

      }




  </script>

</html>
