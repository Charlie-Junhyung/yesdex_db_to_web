<!DOCTYPE html>
<html>
<head>
  <title>YESDEX 2017 출결확인</title>

  <meta charset="utf-8" />

  <link rel="shortcut icon" href="res/favicon.ico">

  <link rel="stylesheet" type="text/css" href="res/style.css" />

  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/parse/1.10.1/parse.min.js"></script>
  <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
  <script type="text/javascript">

  var key = "YesdexaAZx4r86eeoyIwwGfdfOLeT2CnQKFcQ1";
  var url = "http://beaconyx.co.kr:1337/parse";

  Parse.initialize(key);
  Parse.serverURL = url;

  </script>
</head>

<body onload="createDropdownMenu()">

  <div id='container'>
    <table class="top-bar">
        <tr>
          <td vertical-align="middle">
            <select id="select_date" onChange="selectEvent(value)">
              <option value="total">전체 보기</option>
            </select>
          </td>

          <td vertical-align="middle">
            <span style="font-size:24px; font-weight:bold;">강연 출결 현황</span>
          </td>

          <td vertical-align="middle">
            <button class="download-button" id="download" type="button"
            onclick="exportTableToCSV()">
              다운로드
            </button>
          </td>
        </tr>
      </table>

    <hr />

    <table class="read_data" id="read_data" border="1">
      <thead>
        <th>이름</th>
        <th>면허번호</th>
        <th>출입시간</th>
        <th>출입구분</th>
        <th>강연 제목</th>
        <th>연사</th>
        <th>강연날짜</th>
        <th>강연시간</th>
        <th>강연장</th>
      </thead>
      <tbody id="my_tbody">

      </tbody>
    </table>

    <script>
    function createDropdownMenu() {
      var optionData = Parse.Object.extend("TB_TotalData");
      var query = new Parse.Query(optionData);

      query.limit(1000);
      query.find({
        success: function(results) {
          var distinct = [];
          for (var i = 0; i < results.length; i++) {
            var gotTime = results[i].get("TDA_TIME");
            var date = gotTime.split(" ");
            var current = date[0];

            if (!(distinct.includes(current))) {
              b = distinct.includes(current);
              console.log(current + ' is ' + b);
              distinct.push(current);
              addOption(current);
            }
          }

          getTotalData('total');
        },
        error: function(error) {
          alert("ERROR : " + error.code + " " + error.message);
        }
      });
    }

    function addOption(date) {
      var selectObject = document.getElementById("select_date");

      var op = new Option();
      op.value = date; // 값 설정
      op.text = date; // 텍스트 설정

      selectObject.options.add(op); // 옵션 추가
    }

    // read table 'TB_TotalData'
    function getTotalData(value) {
      var tbTotalData = Parse.Object.extend("TB_TotalData");
      var query = new Parse.Query(tbTotalData);
      query.limit(1000);
      if (value !== 'total') {
        query.contains("TDA_TIME", value);
      }
      query.descending("TDA_TIME").find({
        success: function(results) {
          console.log("Successfully retrieved " + results.length);
          var tdaTime;
          var tdaLecture;
          var tdaInout;
          var tdaLecturer;
          var tdaOriginTime;
          var tdaOriginDate;
          var tdaPlace;
          var uuid;
          var usrUserName;
          var usrNumber;
          for (var i = 0; i < results.length; i++) {
            tdaTime = results[i].get("TDA_TIME");
            tdaInout = results[i].get("TDA_INOUT");
            tdaPlace = results[i].get("TDA_PLACE");
            tdaOriginDate = results[i].get("TDA_ORI_DAY");
            tdaOriginTime = results[i].get("TDA_ORI_TIME");
            tdaLecture = results[i].get("TDA_LECTURE");
            tdaLecturer = results[i].get("TDA_LECTURER");

            uuid = results[i].get("TDA_UUID");

            getUserData(uuid, tdaTime, tdaInout, tdaPlace, tdaOriginDate, tdaOriginTime, tdaLecture, tdaLecturer);
          }
        },
        error: function(error) {
          alert("ERROR : " + error.code + " " + error.message);
        }
      });
    }

    //read table 'TB_User_Ko'
    function getUserData(uuid, tdaTime, tdaInout, tdaPlace, tdaOriginDate, tdaOriginTime, tdaLecture, tdaLecturer) {
      var tbUserKo = Parse.Object.extend("TB_User_Ko");
      var query = new Parse.Query(tbUserKo);
      query.equalTo("USR_USER_ID", uuid);
      query.find({
        success: function(results) {
          var usrName;
          var usrNumber;
          usrName = results[0].get("USR_NAME");
          usrNumber = results[0].get("USR_NUMBER");

          add_total_data(tdaTime, tdaInout, tdaPlace, tdaOriginDate, tdaOriginTime, tdaLecture, tdaLecturer, usrName, usrNumber);
        },
        error: function(error) {
          alert("ERROR : " + error.code + " " + error.message);
        }
      });
    }

    // show joined table
    function add_total_data (tdaTime, tdaInout, tdaPlace, tdaOriginDate, tdaOriginTime, tdaLecture, tdaLecturer, usrName, usrNumber) {
      var tbody = document.getElementById('my_tbody');
      var row = tbody.insertRow(my_tbody.rows.length);

      row.insertCell(0).innerHTML = usrName;
      row.insertCell(1).innerHTML = usrNumber;
      row.insertCell(2).innerHTML = tdaTime;
      row.insertCell(3).innerHTML = tdaInout;
      row.insertCell(4).innerHTML = tdaLecture;
      row.insertCell(5).innerHTML = tdaLecturer;
      row.insertCell(6).innerHTML = tdaOriginDate;
      row.insertCell(7).innerHTML = tdaOriginTime;
      row.insertCell(8).innerHTML = tdaPlace;
    }

    // ------------- //

    function selectEvent(value) {
      alert(value);
      if (value == 'total') {
        location.reload(true);
      } else {
        $('#read_data > tbody').empty();
        getTotalData(value);
      }
    }
    </script>
    <script type="text/javascript" src="js/download.js"></script>
  </div>
</body>
</html>
