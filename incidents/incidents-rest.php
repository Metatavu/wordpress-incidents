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
        register_rest_route("incidents", "/incidents/(?P<id>[\d]+)", array(
          'methods' => 'GET',
          'callback' => array($this, 'findIncident')
        ));

        register_rest_route("incidents", "/incidents", array(
          'methods' => 'GET',
          'callback' => array($this, 'listIncidents')
        ));
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
       * @param id
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
       * Returns incident meta date time
       * 
       * @param id
       * @param field_name field name
       */
      function getIncidentMetaDateTime($id, $field_name) {
        $value = $this->getIncidentMeta($id, $field_name);
        if ($value) {
          return date("Y-m-d\TH:i:s", strtotime($value));
        }
        
        return null;
      }

      /**
       * Builds an incident JSON-object
       * 
       * @param incident id
       */
      function buildIncident($id) {
        return [
          'title' => get_the_title($id),
          'severity' => $this->getIncidentMeta($id, 'incident_type'),
          'description' => $this->getIncidentMeta($id, 'description'),
          'detailsLink' => $this->getIncidentMeta($id, 'details_link'),
          'detailsLinkText' => $this->getIncidentMeta($id, 'details_link_text'),
          'areas' => $this->getIncidentMetaTermArray($id, 'areas'),
          'startTime' => $this->getIncidentMetaDateTime($id, 'start_time'),
          'endTime' => $this->getIncidentMetaDateTime($id, 'end_time')
        ];
      }
    
      /**
       * Finds an incident
       * 
       * @param request request
       */
      function findIncident($request) {
        extract($request->get_params());

        if (get_post($id) == null) {
          return 404;
        }

        return $this->buildIncident($id);
      }

      /**
       * Lists incidents
       */
      function listIncidents() {
        $args = array(
          'post_type'=> 'incident',
          'fields'=> 'ids'
        );

        $ids = get_posts($args);
        $incidents = array_map(array($this, 'buildIncident'), $ids);
        return $incidents;
      }
    }
  }

  add_action('rest_api_init',function () {
    new IncidentsRest();
  });
  
?>