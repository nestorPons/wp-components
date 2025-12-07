<?php
/**
 * Uso:
 * get_template_part(
 *     'themes/archivos-historicos-armilla/components/icon-card',
 *     null,
 *     [
 *         'color' => 'primary',
 *         'icon'  => 'fa-calendar-o',
 *         'title' => 'Agenda',
 *         'link'  => '/agenda'
 *     ]
 * );
 *
 * Variables PHP:
 * - $color: token de color para el círculo (.service-icon-circle).
 * - $icon: clase FontAwesome para el ícono.
 * - $title: texto visible del titular.
 * - $link: URL destino del ícono y el título.
 *
 * CSS:
 * - .service-icon controla la alineación y el layout general.
 * - .service-icon-circle usa var(--color-<token>) para el fondo y centra el ícono.
 * - .service-icon-title define tipografía y espaciado del texto enlazado.
 */
$color = $args['color'];
$icon = $args['icon'];
$title = $args['title'];
$link = $args['link'];
?>

<div class="service-icon">
    <a href="<?php echo esc_url($link); ?>" class="service-icon-circle" style="background: var(--color-<?= $color?>)">
        <i class="fa fa-solid <?=$icon?>"></i>
    </a>
    <h3 class="service-icon-title">
        <a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a>
    </h3>
</div>
<style>
    .service-icon {
        text-align: center;
        margin: 20px auto;
        width: fit-content;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .service-icon-circle {
        width: 120px;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        text-decoration: none;
    }

    .service-icon-circle i {
        font-size: 42px;
        color: #fff;
    }

    .service-icon-title {
        width: 100%;
        margin-top: 12px;
        font-size: 18px;
        font-weight: 600;
    }

    .service-icon-title a {
        text-decoration: none;
        color: #333;
    }

    .service-icon-title a:hover {
        opacity: 0.7;
    }
</style>    