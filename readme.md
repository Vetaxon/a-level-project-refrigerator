<p>Refrigerator API</p>

<p>An instruction for BackEnd</p>
<ul>
<li>clone repo</li>
<li>composer install</li>
<li>configure .env</li>
<li>php artisan key:generate</li>
<li>php artisan jwt:secret</li>
<li>php artisan config:cache</li>
<li>php artisan migrate</li>
<li>php artisan db:seed</li>
</ul>

<p>Do not forget to add into .gitignore: .env  .idea/ vendor/ .gitignore  and other custom files or dirs.</p>

<p>API Documentation for FrontEnd</p>
<p>http://refrigerator-alevel.tk<p/>
<p>USER<p/>

<p>
Url: /auth/register<br>
Method: POST<br> 
Header: {Content-Type: application/json, Accept: application/json}<br>
Body: { "name":"name",
      	"email":"name@test.com",
      	"password":"111111",
      	"password_confirmation":"111111"
      }
</p>

<p>
Url: /auth/login<br>
Method: POST<br> 
Header: {Content-Type: application/json, Accept: application/json}<br>
Body: { "email":"name@test.com",
      	"password":"111111"      	
      }
</p>

<p>
Url: /auth/user<br>
Method: GET<br> 
Header: {Content-Type: application/json, Accept: application/json, Authorization: Bearer ghhdjd...}<br>
</p>

<p>
Url: /auth/user<br>
Method: PUT<br> 
Header: {Content-Type: application/json, Accept: application/json, Authorization: Bearer ghhdjd...}<br>
Body: {
      	"name":"newname",
      	"email":"newemail@test.com"	
      }
</p>

<p>
Url: /auth/user/password<br>
Method: PUT<br> 
Header: {Content-Type: application/json, Accept: application/json, Authorization: Bearer ghhdjd...}<br>
Body: {
      	"password":"newpassword",
      	"password_confirmation":"newpassword"	
      }
</p>

<p>
Url: /auth/user<br>
Method: DELETE<br> 
Header: {Content-Type: application/json, Accept: application/json, Authorization: Bearer ghhdjd...}<br>
</p>

<p>INGREDIENTS<p/>

<p>
Url: /measures<br>
Method: GET<br> 
Header: {Accept: application/json}<br>
</p>

<p>
Url: /ingredients<br>
Method: GET<br> 
Header: {Accept: application/json, Authorization: Bearer ghhdjd...}<br>
</p>

<p>
Url: /ingredients/id<br>
Method: GET<br> 
Header: {Accept: application/json, Authorization: Bearer ghhdjd...}<br>
</p>

<p>
Url: /ingredients<br>
Method: POST<br> 
Header: {Content-Type: application/json, Accept: application/json, Authorization: Bearer ghhdjd...}<br>
Body: {
      	"name":"juice",
      	"measure_id":"1"
      }
</p>

<p>
Url: /ingredients/id<br>
Method: PUT<br> 
Header: {Content-Type: application/json, Accept: application/json, Authorization: Bearer ghhdjd...}<br>
Body: {
      	"name":"juice"      	
      }
</p>

<p>
Url: /ingredients/id<br>
Method: PUT<br> 
Header: {Content-Type: application/json, Accept: application/json, Authorization: Bearer ghhdjd...}<br>
Body: {
      	"measure_id":"2"      	
      }
</p>

<p>
Url: /ingredients/id<br>
Method: DELETE<br> 
Header: {Accept: application/json, Authorization: Bearer ghhdjd...}<br>
</p>
