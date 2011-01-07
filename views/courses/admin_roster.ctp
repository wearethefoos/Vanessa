<?php
   $placements->display_activities();
   $placements->display_preferences();
   $placements->display_placements($result['placements'], $result['end_score'], $result['nb_solutions'], $result['nb_generations']);
   $placements->display_scores($result['score_history']);
?>
