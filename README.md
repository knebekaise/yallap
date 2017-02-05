**Get use auth_key**
----
  Use this method once to take user auth key. Then you had to save this key and use in authorization header for other request:
  Authorization: Bearer <your_auth_key>

* **/v1/users**

* **Method:**

  `POST`



* **Success Response:**

  * **Code:** 201 CREATED<br />
    **Content:** `{"registration_date":1486326235,"auth_key":"179d55154fa8f022d672743429362595","id":"4"}`

**Get user info**
----
* **/v1/users**

* **Method:**

  `GET`

* **Success Response:**

  * **Code:** 200 OK<br />
    **Content:** `{"registration_date":1486326235,"auth_key":"179d55154fa8f022d672743429362595","id":"4"}`

  * **Error Response:**

  If user with such auth key does not found
  * **Code:** 401 UNAUTHORIZED <br />
    **Content:** `{"name":"Unauthorized","message":"Your request was made with invalid credentials.","code":0,"status":401,"type":"yii\\web\\UnauthorizedHttpException"}`

**Create new video task**
----
* **/v1/videos**

* **Method:**

  `POST`

* **Data Params**
    video - required, file, video for cutting
    start_time - required, integer, start time to cut
    end_time - required, integer, end time to cut, must be more then start time

* **Success Response:**

  * **Code:** 201 CREATED<br />
    **Content:** `{"start_time":"100","end_time":"200","user_id":"1","status":"new","original_file_name":"John Cantlie and ISIS Video Strategy - Video - NYTimescom.mp4","file_name":"213256613258978cea504075.97835815.mp4","id":"29"}`

  * **Error Response:**

  If user with such auth key does not found
  * **Code:** 401 UNAUTHORIZED <br />
    **Content:** `{"name":"Unauthorized","message":"Your request was made with invalid credentials.","code":0,"status":401,"type":"yii\\web\\UnauthorizedHttpException"}`

  OR

  * **Code:** 422 DATA VALIDATION FAILED <br />
    **Content:** `[{"field":"video","message":"Please upload a file."}]`


**View video tasks list**
----
* **/v1/videos**

* **Method:**

  `GET`

*  **URL Params**

   **Optional:**

   `page=[numeric]`
   `per_page=[numeric]`

* **Success Response:**

  * **Code:** 200 OK<br />
    **Content:** `[{"start_time":"100","end_time":"100","user_id":"1","status":"new","original_file_name":"This Weeks Movies Jan 27 2017 - Video - NYTimescom.mp4","file_name":"9045725475897482a344804.48744141.mp4","id":"1"},{"start_time":"100","end_time":"100","user_id":"1","status":"new","original_file_name":"This Weeks Movies Jan 27 2017 - Video - NYTimescom.mp4","file_name":"8485893458974e4c9ee212.12696707.mp4","id":"2"}]`

  * **Error Response:**

  If user with such auth key does not found
  * **Code:** 401 UNAUTHORIZED <br />
    **Content:** `{"name":"Unauthorized","message":"Your request was made with invalid credentials.","code":0,"status":401,"type":"yii\\web\\UnauthorizedHttpException"}`

**View video tasks info**
----
* **/v1/videos/:id**

* **Method:**

  `GET`

*  **URL Params**

   **Required:**

   `id=[integer]`

* **Success Response:**

  * **Code:** 200 OK<br />
    **Content:** `{"start_time":"100","end_time":"100","user_id":"1","status":"new","original_file_name":"This Weeks Movies Jan 27 2017 - Video - NYTimescom.mp4","file_name":"1479487936589773bf98c0f8.14478161.mp4","id":"10"}`

  * **Error Response:**

  If user with such auth key does not found
  * **Code:** 401 UNAUTHORIZED <br />
    **Content:** `{"name":"Unauthorized","message":"Your request was made with invalid credentials.","code":0,"status":401,"type":"yii\\web\\UnauthorizedHttpException"}`

  OR

  If task with such id does not found
  * **Code:** 404 NOT FOUND <br />
    **Content:** `{"name":"Not Found","message":"Object not found: 100","code":0,"status":404,"type":"yii\\web\\NotFoundHttpException"}`


**Restart failed task**
----
* **/v1/videos/:id**

* **Method:**

  `PUT`

*  **URL Params**

   **Required:**

   `id=[integer]`

* **Success Response:**

  * **Code:** 200 OK<br />
    **Content:** `{"start_time":"100","end_time":"200","user_id":"1","status":"waiting","original_file_name":"John Cantlie and ISIS Video Strategy - Video - NYTimescom.mp4","file_name":"1893928635589795baceec90.08811456.mp4","id":"38"}`

  OR

  If tried to restart non failed task
  * **Code:** 304 NOT MODIFIED<br />

  * **Error Response:**

  If user with such auth key does not found
  * **Code:** 401 UNAUTHORIZED <br />
    **Content:** `{"name":"Unauthorized","message":"Your request was made with invalid credentials.","code":0,"status":401,"type":"yii\\web\\UnauthorizedHttpException"}`

  OR

  If task with such id does not found
  * **Code:** 404 NOT FOUND <br />
    **Content:** `{"name":"Not Found","message":"Object not found: 100","code":0,"status":404,"type":"yii\\web\\NotFoundHttpException"}`
