<p>Refrigerator API</p>
<hr>
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
<li>php artisan storage:link</li>
<li>php artisan queue:work</li>
<li>php artisan self-diagnosis</li>
</ul>

<p>Do not forget to add into .gitignore: .env  .idea/ vendor/ .gitignore  and other custom files or dirs.</p>
<br>
<hr>
<p>API Documentation for FrontEnd</p>
<p>http://refrigerator-alevel.tk/api<p/>
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
        "old_password":"oldpassword",
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
      	"name":"juice"      	
      }
</p>

<p>
Url: /ingredients/id<br>
Method: PUT<br> 
Header: {Content-Type: application/json, Accept: application/json, Authorization: Bearer ghhdjd...}<br>
Body: {
      	"name":"juice new"	
      }
</p>


<p>
Url: /ingredients/id<br>
Method: DELETE<br> 
Header: {Accept: application/json, Authorization: Bearer ghhdjd...}<br>
</p>

<p>RECIPES<p/>

<p>
Url: /recipes<br>
Method: GET<br> 
Header: {Accept: application/json, Authorization: Bearer ghhdjd...}<br>
Api route to get all vavilable recipes for user(not recommended)
</p>

<p>
Url: /recipes/id<br>
Method: GET<br> 
Header: {Accept: application/json, Authorization: Bearer ghhdjd...}<br>
</p>

<p>
Url: /recipes<br>
Method: POST<br> 
Header: {Content-Type: application/json, Accept: application/json, Authorization: Bearer ghhdjd...}<br>
Body: {
      	"name":"рецепт тест",
      	"text":"ddddddddddddddd",
      	"ingredients":[
      		{"juice":"1 шт"},
      		{"морковь":"1 шт"},
      		{"вода":"1 л"},
      		{"сода":" 1 г"}
      	]	
      }
</p>

<p>
Url: /recipes/id<br>
Method: PUT<br> 
Header: {Content-Type: application/json, Accept: application/json, Authorization: Bearer ghhdjd...}<br>
Body: {
      	"name":"рецепт тест",
      	"text":"ddddddddddddddd",
      	"ingredients":[
      		{"juice":"1 l"},
      		{"морковь":"1 шт"},
      		{"вода":"1 л"},
      		{"сода":" 1 г"}
      	]	
      }
<br>
Fields "name", "text", "ingredients" are not required
</p>


<p>
Url: /recipes/id<br>
Method: DELETE<br> 
Header: {Accept: application/json, Authorization: Bearer ghhdjd...}<br>
</p>

<p>REFRIGERATORS<p/>

<p>
Url: /refrigerator/ingredients<br>
Method: GET<br> 
Header: {Accept: application/json, Authorization: Bearer ghhdjd...}<br>
User's ingredients in the refrigerator
</p>

<p>
Url: /refrigerator/ingredients/id<br>
Method: GET<br> 
Header: {Accept: application/json, Authorization: Bearer ghhdjd...}<br>
User's ingredient by id
</p>

<p>
Url: /refrigerator/ingredients<br>
Method: POST<br> 
Header: {Content-Type: application/json, Accept: application/json, Authorization: Bearer ghhdjd...}<br>
Body: {
      	"ingredient_id":"12",
      	"amount":"40 g"
      }
</p>

<p>
Url: /refrigerator/ingredients/id<br>
Method: PUT<br> 
Header: {Content-Type: application/json, Accept: application/json, Authorization: Bearer ghhdjd...}<br>
Body: {      	
      	"amount":"40 ff"
      }
</p>


<p>
Url: /refrigerator/ingredients/id<br>
Method: DELETE<br> 
Header: {Accept: application/json, Authorization: Bearer ghhdjd...}<br>
Delete user's ingredient from refrigerator
</p>

<p>
Url: /refrigerator/recipes<br>
Method: GET<br> 
Header: {Accept: application/json, Authorization: Bearer ghhdjd...}<br>
Api route to get recommended recipes for user (paginated by 9)
</p>