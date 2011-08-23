<br />
TWIG TPL:
{% for getQuery in ModelsHelloWorld_getQuery %}   
	NAME: {{ getQuery.NAME }}<br/>
	ID: {{ getQuery.ID }}<br/>
{% endfor %}
