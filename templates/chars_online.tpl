{% extends "base.tpl" %}

{% block content %}
Online Players: {{ chars_count }}<br />
Online Allianz: {{ ally_count }} | Online Horde: {{ horde_count }}

<div class="border tabMenuContent">
{% if chars_count > 0 %}	
  <table class="tableList charactersList">			                
      <thead>				                   
        <tr class="tableHead">													 							                 
          <th class="columnName active">								                   
            <div>									                     
              <a href="#">Name</a>								                   
            </div>							                 
          </th>													 							                 
          <th class="columnDATAlevel">								                   
            <div>									                     
              <a href="#">Level</a>								                   
            </div>							                 
          </th>													 							                 
          <th class="columnRace">								                   
            <div>									                     
              <a href="#">Rasse</a>								                   
            </div>							                 
          </th>
          <th class="columnClass">								                   
            <div>									                     
              <a href="#">Klasse</a>								                   
            </div>							                 
          </th>	 	         					 							                 
          <th class="columnMap,zone">								                   
            <div>									                     
              <a href="#">Map</a>								                   
            </div>							                 
          </th>
          <th class="columnMap,zone">								                   
            <div>									                     
              <a href="#">Zone</a>								                   
            </div>							                 
          </th>
          <th class="columnMap,zone">								                   
            <div>									                     
              <a href="#">Playtime</a>								                   
            </div>							                 
          </th>			 											               
        </tr>				             
      </thead>
      <tbody>
  		{% for char in chars %}
  			<tr class="container-1">
      		<td class="columnName">{{ char.data.name }}</td>
      		<td class="columnDATAlevel">{{ char.data.level }}</td>
      		<td class="columnRaceImg">{{ char|race_icon }}</td>
      		<td class="columnClass">{{ char|class_icon }}</td>
      		<td class="columnAreaName">{{ char|map_name }}</td>
      		<td class="columnAreaName">{{ char|zone_name }}</td>
      		<td class="columnName">{{ char.data.totaltime|uptime }}</td>
  			</tr>
  		{% endfor %}
 		</tbody>			           
 	</table>
	<br />
{% else %}
	<b>No players that are currently playing in ChronosWoW!</b>
{% endif %}
</div>
{% endblock content %}