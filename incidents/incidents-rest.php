<?php
  namespace Incidents;

  if (!defined('ABSPATH')) { 
    exit;
  }

  if (!class_exists( 'Incidents\IncidentsRest' ) ) {
    class IncidentsRest {
      public function __construct() {
        register_rest_route("incidents", "/(?P<id>[\d]+)", array(
          'methods' => 'GET',
          'callback' => array($this, 'getSingleIncident')
        ));
      }

      function getIncidentMeta($id, $field_name) {
        $value = get_post_meta($id, $field_name, true);        
        if ($value) {
          return $value;
        }
        
        return null;
      }
      
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
      
      function getIncidentMetaDateTime($id, $field_name) {
        $value = $this->getIncidentMeta($id, $field_name);
        if ($value) {
          return date("Y-m-d\TH:i:s", strtotime($value));
        }
        
        return null;
      }

      function buildIncident($id) {
        return [
          'incident_type' => $this->getIncidentMeta($id, 'incident_type'),
          'description' => $this->getIncidentMeta($id, 'description'),
          'details_link' => $this->getIncidentMeta($id, 'details_link'),
          'details_link_text' => $this->getIncidentMeta($id, 'details_link_text'),
          'areas' => $this->getIncidentMetaTermArray($id, 'areas'),
          'start_time' => $this->getIncidentMetaDateTime($id, 'start_time'),
          'end_time' => $this->getIncidentMetaDateTime($id, 'end_time')
        ];
      }
    
      function getSingleIncident($request) {
        extract($request->get_params());
        return $this->buildIncident($id);
      }
    }
  }

  

  add_action('rest_api_init',function () {
    new IncidentsRest();
  });
  
?>