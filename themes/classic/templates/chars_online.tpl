{% extends "base.tpl" %}

{% block content %}
<div id="content_title_charson"></div>
<br /><br /><br /><br />
Online Chars: {{ chars_count }} | Online GMs: {{ gms_count }}<br />
Online Allianz: {{ ally_count }} | Online Horde: {{ horde_count }}
<br /><br />
<div>

{% if gms_count > 0 %}	
  <div class="border tabMenuContent">      
      <table class="tableList charactersList">			   
        <thead>				    
          <tr class="tableHead">													 							
            <th class="columnName active">								
                <div>									                     
                  <a href="chars_online.php?realm={{ realm_id }}&sort=name&order={{ sort_order }}">Name</a>								              	</div>							                 
          	</th>
			<th class="faction active">								                   
                <div>									                     
                  <a href="chars_online.php?realm={{ realm_id }}&sort=faction&order={{ sort_order }}">Fraktion</a>								                   
                </div>							                 
          	</th>
          	<th class="columnDATAlevel active">								
                <div>									                     
                  <a href="chars_online.php?realm={{ realm_id }}&sort=level&order={{ sort_order }}">Level</a>								            	</div>							                 
          	</th>													 							                 
          	<th class="columnRace active">								                   
                <div>									                     
                  <a href="chars_online.php?realm={{ realm_id }}&sort=race&order={{ sort_order }}">Rasse</a>								            	</div>							                 
          	</th>
          	<th class="columnClass active">								                   
                <div>									                     
                  <a href="chars_online.php?realm={{ realm_id }}&sort=class&order={{ sort_order }}">Klasse</a>								            	</div>							                 
          	</th>	 	         					 							                 
          	<th class="columnMap,zone active">								                   
            <div>									                     
              <a href="chars_online.php?realm={{ realm_id }}&sort=map&order={{ sort_order }}">Map</a>								                   
            </div>							                 
          </th>
          <th class="columnMap,zone active">								                   
            <div>									                     
              <a href="chars_online.php?realm={{ realm_id }}&sort=zone&order={{ sort_order }}">Zone</a>								                   
            </div>							                 
          </th>
          <th class="playtime active">								                   
            <div>									                     
              <a href="chars_online.php?realm={{ realm_id }}&sort=totaltime&order={{ sort_order }}">Spielzeit</a>								                   
            </div>							                 
          </th>			 											               
        </tr>				             
      </thead>
			<tbody>
				{% for char in gms %}
	  			<tr class="container-1">
                    <td class="name">{{ char.data.name }}</td>
                    <td class="faction">{{ char|faction_icon }}</td>
                    <td class="level">{{ char|faction_icon }}</td>
                    <td class="race">{{ char|faction_icon }}</td>
                    <td class="class">{{ char|faction_icon }}</td>
                    <td class="area">{{ char|faction_icon }}</td>
                    <td class="area">{{ char|faction_icon }}</td>
                    <td class="playtime">{{ char|faction_icon }}</td>
	  			</tr>
	  		{% endfor %}
			</tbody>
        </table>
    </div>
    <br />   
{% endif %}
{% if chars_count > 0 %}
    <div class="border tabMenuContent">      
      <table class="tableList charactersList">			   
                <thead>				    
          <tr class="tableHead">													 							
            <th class="columnName active">								
                <div>									                     
                  <a href="chars_online.php?realm={{ realm_id }}&sort=name&order={{ sort_order }}">Name</a>								              	</div>							                 
          	</th>
			<th class="faction active">								                   
                <div>									                     
                  <a>Fraktion</a>								                   
                </div>							                 
          	</th>
          	<th class="columnDATAlevel active">								
                <div>									                     
                  <a href="chars_online.php?realm={{ realm_id }}&sort=level&order={{ sort_order }}">Level</a>								            	</div>							                 
          	</th>													 							                 
          	<th class="columnRace active">								                   
                <div>									                     
                  <a href="chars_online.php?realm={{ realm_id }}&sort=race&order={{ sort_order }}">Rasse</a>								            	</div>							                 
          	</th>
          	<th class="columnClass active">								                   
                <div>									                     
                  <a href="chars_online.php?realm={{ realm_id }}&sort=class&order={{ sort_order }}">Klasse</a>								            	</div>							                 
          	</th>	 	         					 							                 
          	<th class="columnMap,zone active">								                   
            <div>									                     
              <a href="chars_online.php?realm={{ realm_id }}&sort=map&order={{ sort_order }}">Map</a>								                   
            </div>							                 
          </th>
          <th class="columnMap,zone active">								                   
            <div>									                     
              <a href="chars_online.php?realm={{ realm_id }}&sort=zone&order={{ sort_order }}">Zone</a>								                   
            </div>							                 
          </th>
          <th class="playtime active">								                   
            <div>									                     
              <a href="chars_online.php?realm={{ realm_id }}&sort=totaltime&order={{ sort_order }}">Spielzeit</a>								                   
            </div>							                 
          </th>			 											               
        </tr>				             
      </thead>
      <tbody>
  		{% for char in chars %}
  			<tr class="container-1">
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
</div>
{% endblock content %}