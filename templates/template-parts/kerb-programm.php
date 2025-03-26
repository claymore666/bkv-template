<?php
// Kerb-Tage definieren
$kerb_days = array(
    'donnerstag' => 'Donnerstag, 14. August',
    'freitag' => 'Freitag, 15. August',
    'samstag' => 'Samstag, 16. August',
    'sonntag' => 'Sonntag, 17. August',
);

$current_day = isset($_GET['day']) ? sanitize_text_field($_GET['day']) : 'donnerstag';
?>

<section class="festival-program">
  <div class="day-selector">
    <ul class="tabs">
      <?php foreach ($kerb_days as $day_id => $day_label) : ?>
        <li <?php echo $current_day === $day_id ? 'class="active"' : ''; ?> data-day="<?php echo esc_attr($day_id); ?>">
          <?php echo esc_html(explode(',', $day_label)[0]); ?>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
  
  <div class="schedule-container">
    <?php foreach ($kerb_days as $day_id => $day_label) : ?>
      <div class="day-schedule <?php echo $current_day === $day_id ? 'active' : ''; ?>" id="<?php echo esc_attr($day_id); ?>">
        <h2><?php echo esc_html($day_label); ?></h2>
        
        <div class="timeline">
          <?php
          // WP Query für Events des aktuellen Tages
          $events_query = new WP_Query(array(
            'post_type' => 'kerb_event',
            'posts_per_page' => -1,
            'meta_key' => 'event_time',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'tax_query' => array(
              array(
                'taxonomy' => 'kerb_day',
                'field' => 'slug',
                'terms' => $day_id,
              ),
            ),
          ));
          
          if ($events_query->have_posts()) :
            while ($events_query->have_posts()) : $events_query->the_post();
              $event_time = get_field('event_time');
              $event_location = get_field('event_location');
              $is_highlight = get_field('is_highlight');
          ?>
              <div class="event <?php echo $is_highlight ? 'highlight' : ''; ?>">
                <div class="time"><?php echo esc_html($event_time); ?></div>
                <div class="event-details">
                  <h3><?php the_title(); ?></h3>
                  <?php if ($event_location) : ?>
                    <div class="location"><?php echo esc_html($event_location); ?></div>
                  <?php endif; ?>
                  <div class="description">
                    <?php the_content(); ?>
                  </div>
                </div>
              </div>
          <?php
            endwhile;
            wp_reset_postdata();
          else :
          ?>
            <div class="no-events">
              <p>Keine Veranstaltungen für diesen Tag eingetragen.</p>
            </div>
          <?php endif;
?>
