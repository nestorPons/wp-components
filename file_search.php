<?php

$search_query = isset($_GET['q']) ? trim(sanitize_text_field($_GET['q'])) : '';

/* Si no hay búsqueda, no devolver resultados */
$no_query = ($search_query === '' || strlen($search_query) < 1);

/* Función: extender búsqueda solo si hay query */
function extend_media_search($where, $wp_query) {
    global $wpdb;

    if (empty($wp_query->query_vars['media_search'])) {
        return $where;
    }

    $s = esc_sql($wp_query->query_vars['media_search']);

    $where .= " AND (
        {$wpdb->posts}.post_title LIKE '%{$s}%'
        OR {$wpdb->posts}.post_content LIKE '%{$s}%'
        OR {$wpdb->posts}.post_excerpt LIKE '%{$s}%'
        OR EXISTS (
            SELECT * FROM {$wpdb->postmeta}
            WHERE {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID
            AND {$wpdb->postmeta}.meta_key = '_wp_attachment_image_alt'
            AND {$wpdb->postmeta}.meta_value LIKE '%{$s}%'
        )
    )";

    return $where;
}
?>

<div class="media-search-wrapper">

    <form class="media-search-form" method="get">
        <input type="text" name="q" class="media-search-input"
               value="<?php echo esc_attr($search_query); ?>"
               placeholder="Buscar archivos…">
        <button class="media-search-button">Buscar</button>
    </form>

    <div class="media-search-results">

        <?php if ($no_query): ?>

            <p>Introduce un término de búsqueda.</p>

        <?php else: ?>

            <?php
            add_filter('posts_where', 'extend_media_search', 10, 2);

            $query = new WP_Query([
                'post_type'      => 'attachment',
                'post_status'    => 'inherit',
                'posts_per_page' => -1,
                'orderby'        => 'date',
                'order'          => 'DESC',
                'media_search'   => $search_query
            ]);

            remove_filter('posts_where', 'extend_media_search');
            ?>

            <?php if ($query->have_posts()): ?>
                <?php while ($query->have_posts()): $query->the_post(); ?>

                    <?php
                    $url   = wp_get_attachment_url(get_the_ID());
                    $type  = get_post_mime_type();
                    $alt   = get_post_meta(get_the_ID(), '_wp_attachment_image_alt', true);
                    $title = get_the_title();
                    $caption = get_the_excerpt();
                    ?>

                    <div class="media-item">
                        <div>
                            <strong><?php echo esc_html($title); ?></strong><br>
                            <?php if ($caption): ?>
                                <small><em><?php echo esc_html($caption); ?></em></small><br>
                            <?php endif; ?>
                            <a href="<?php echo esc_url($url); ?>" target="_blank">Abrir archivo</a>
                        </div>
                        <span class="media-type"><?php echo esc_html($type); ?></span>
                    </div>

                <?php endwhile; ?>
            <?php else: ?>
                <p>No se encontraron archivos.</p>
            <?php endif; ?>

            <?php wp_reset_postdata(); ?>

        <?php endif; ?>

    </div>
</div>

<style>
.media-search-wrapper { max-width: 800px; margin: auto; }
.media-search-form { display: flex; gap: 10px; margin-bottom: 20px; }
.media-search-input { flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 6px; }
.media-search-button { padding: 10px 20px; background: var(--color-tertiary); color: var(--color-white); border: none; border-radius: 6px; cursor: pointer; }
.media-search-results { display: grid; gap: 12px; }
.media-item { padding: 12px; border: 1px solid #ddd; border-radius: 6px; background: #fafafa; display: flex; justify-content: space-between; }
.media-item a { color: var(--color-tertiary); font-weight: 600; }
.media-type { opacity: 0.7; }
</style>

<?php wp_reset_postdata(); ?>
