<?php
  namespace Incidents;

  if (!defined('ABSPATH')) { 
    exit;
  }

  if (!class_exists( 'Incidents\IncidentsRest' ) ) {

    /**
     * Creates REST routes for incidents
     */
    class IncidentsRest {
      public function __construct() {
        register_rest_route("incidents", "/incidents(?:/?area=(?P<area>\d+))?", [
          'methods' => 'GET',
          'callback' => [$this, 'listIncidents']
        ]);
      }

      /**
       * Returns incident meta
       * 
       * @param id incident id
       * @param field_name field name
       */
      function getIncidentMeta($id, $field_name) {
        $value = get_post_meta($id, $field_name, true);        
        if ($value) {
          return $value;
        }
        
        return null;
      }
      
      /**
       * Returns incident meta term array
       * 
       * @param id incident id
       * @param field_name field name
       */
      function getIncidentMetaTermArray($id, $field_name) {
        $result = [];
        
        $value = $this->getIncidentMeta($id, $field_name);
        if ($value) {
          if (!is_array($value)) {
            return $result;
          }
          
          foreach ($value as $termId) {
            $term = get_term(intval($termId));
            if ($term) {
              $result[] = $term->name;
            }
          }
        }
        
        return $result;
      }
      
      /**
       * Returns incident meta timestamp
       * 
       * @param id incident id
       * @param field_name field name
       */
      function getIncidentMetaTimestamp($id, $field_name) {
        $value = $this->getIncidentMeta($id, $field_name);
        if ($value) {
          return strtotime($value);
        }
        
        return null;
      }

      /**
       * Builds an incident JSON-object
       * 
       * @param id incident id
       */
      function buildIncident($id) {
        return [
          'title' => get_the_title($id),
          'severity' => $this->getIncidentMeta($id, 'incident_type'),
          'description' => $this->getIncidentMeta($id, 'description'),
          'detailsLink' => $this->getIncidentMeta($id, 'details_link'),
          'detailsLinkText' => $this->getIncidentMeta($id, 'details_link_text')
        ];
      }
      
      /**
       * Lists incidents
       */
      function listIncidents($request) {
        $area = $request->get_param('area');
        
        $args = [
          'post_type'=> 'incident',
          'fields'=> 'ids'
        ];

        $ids = get_posts($args);
        $filteredIds = array_values(array_filter($ids, function ($id) use($area){
          $startTime = $this->getIncidentMetaTimestamp($id, 'start_time');
          $endTime = $this->getIncidentMetaTimestamp($id, 'end_time');
          $currentTime  = strtotime(date('Y-m-d\TH:i:s'));
  
          if ($startTime != null && $currentTime < $startTime) {
            return false;
          }
  
          if ($endTime != null && $endTime < $currentTime) {
            return false;
          }
  
          $areas = $this->getIncidentMetaTermArray($id, 'areas');
          if (!in_array($area, $areas)) {
            return false;
          }

          return true;
        }));

        $incidents = array_map([$this, 'buildIncident'], $filteredIds);

        return $incidents;
      }
    }
  }

  add_action('rest_api_init',function () {
    new IncidentsRest();
  });
  
?>