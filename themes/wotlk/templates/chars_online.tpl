{% extends "base.tpl" %}

{% block content %}
<div id="content_title_charson"></div><br />
Online Chars: {{ chars_count }} | Online GMs: {{ gms_count }}<br />
Online Allianz: {{ ally_count }} | Online Horde: {{ horde_count }}

<div>
{% if chars_count > 0 %}	
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
				{% for char in gms %}
	  			<tr class="char">
	      		<td class="name">{{ char.data.name }}</td>
						<td class="faction">{{ char|faction_icon }}</td>
	      		<td class="level">{{ char.data.level }}</td>
	      		<td class="race">{{ char|race_icon }}</td>
	      		<td class="class">{{ char|class_icon }}</td>
	      		<td class="area">{{ char|map_name }}</td>
	      		<td class="area">{{ char|zone_name }}</td>
	      		<td class="playtime">{{ char.data.totaltime|uptime }}</td>
	  			</tr>
	  		{% endfor %}
			</tbody>
      <tbody>
  		{% for char in chars %}
  			<tr class="char">
      		<td class="name">{{ char.data.name }}</td>
					<td class="faction">{{ char|faction_icon }}</td>
      		<td class="level">{{ char.data.level }}</td>
      		<td class="race">{{ char|race_icon }}</td>
      		<td class="class">{{ char|class_icon }}</td>
      		<td class="area">{{ char|map_name }}</td>
      		<td class="area">{{ char|zone_name }}</td>
      		<td class="playtime">{{ char.data.totaltime|uptime }}</td>
  			</tr>
  		{% endfor %}
 		</tbody>			           
 	</table>
	<br />
{% else %}
	<b>No players that are currently playing!</b>
{% endif %}
</div>
{% endblock content %}