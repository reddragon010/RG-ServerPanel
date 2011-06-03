{% extends "base.tpl" %}

{% block content %}
<table>
	<tr>
		<th>Username</th>
		<td>{{user.username}}</td>
	</tr>
	<tr>
		<th>ID</th>
		<td>{{user.id}}</td>
	</tr>
	<tr>
		<th>EMail</th>
		<td>{{user.email}}</td>
	</tr>
	<tr>
		<th>JoinDate</th>
		<td>{{user.joindate}}</td>
	</tr>
	<tr>
		<th>LastIP</th>
		<td>{{user.last_ip}}</td>
	</tr>
	<tr>
		<th>Status</th>
		<td>{{user|user_status}}</td>
	</tr>	

<table class="tableList charactersList">			                
    <thead>				                   
      <tr>													 							                 
        <th class="name active">								                   
          <div>									                     
            <a href="chars_online.php?realm={{ realm_id }}&sort=name&order={{ sort_order }}">Name</a>								                   
          </div>							                 
        </th>
				<th class="faction">								                   
          <div>									                     
            <a>Fraktion</a>								                   
          </div>							                 
        </th>
        <th class="level">								                   
          <div>									                     
            <a href="chars_online.php?realm={{ realm_id }}&sort=level&order={{ sort_order }}">Level</a>								                   
          </div>							                 
        </th>													 							                 
        <th class="race">								                   
          <div>									                     
            <a href="chars_online.php?realm={{ realm_id }}&sort=race&order={{ sort_order }}">Rasse</a>								                   
          </div>							                 
        </th>
        <th class="class">								                   
          <div>									                     
            <a href="chars_online.php?realm={{ realm_id }}&sort=class&order={{ sort_order }}">Klasse</a>								                   
          </div>							                 
        </th>	 	         					 							                 
        <th class="area">								                   
          <div>									                     
            <a href="chars_online.php?realm={{ realm_id }}&sort=map&order={{ sort_order }}">Map</a>								                   
          </div>							                 
        </th>
        <th class="area">								                   
          <div>									                     
            <a href="chars_online.php?realm={{ realm_id }}&sort=zone&order={{ sort_order }}">Zone</a>								                   
          </div>							                 
        </th>
        <th class="playtime">								                   
          <div>									                     
            <a href="chars_online.php?realm={{ realm_id }}&sort=totaltime&order={{ sort_order }}">Spielzeit</a>								                   
          </div>							                 
        </th>			 											               
      </tr>				             
    </thead>
    <tbody>
		{% for char in user.characters %}
			<tr class="char">
    		<td class="name">{{ char.name }}</td>
				<td class="faction">{{ char|factionicon }}</td>
    		<td class="level">{{ char.level }}</td>
    		<td class="race">{{ char|raceicon }}</td>
    		<td class="class">{{ char|classicon }}</td>
    		<td class="area">{{ char|mapname }}</td>
    		<td class="area">{{ char|zonename }}</td>
    		<td class="playtime">{{ char.totaltime|uptime }}</td>
			</tr>
		{% endfor %}
	</tbody>			           
</table>
{% endblock %}