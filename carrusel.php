<?php
/**
 * Carrusel component requirements
 *
 * PHP $args keys:
 * - images(array<string>) URLs mostradas en el slider (obligatorio para contenido).
 * - title(string) Título mostrado en la superposición.
 * - subtitle(string) Subtítulo del carrusel.
 * - buttons(array<['label','url','type']>) Botones opcionales; type acepta primary|secondary.
 * - filters(array<string>) Permite aplicar efectos; actualmente soporta 'bw' (grayscale).
 * - show_navs(bool) Controla visibilidad de flechas y puntos (true por defecto).
 *
 * CSS custom properties necesarias en el tema:
 * - --max-width Ancho máximo del contenedor del slider.
 * - --color-white Color base del texto y dots.
 * - --color-tertiary Color para botón primary hover/border.
 * - --color-secondary Color para botón secondary hover/border.
 */

$images = $args['images'] ?? [];
$title = $args['title'] ?? '';
$subtitle = $args['subtitle'] ?? '';
$buttons = $args['buttons'] ?? [];
$images_filter = $args['filters'] ?? [];
$show_navs = $args['show_navs'] ?? true;
?>

<div class="slider-wrapper">

    <?php if ($title || $subtitle || !empty($buttons)): ?>
    <div class="slider-content">
        <?php if ($title): ?>
            <h1 class="slider-title"><?php echo esc_html($title); ?></h1>
        <?php endif; ?>

        <?php if ($subtitle): ?>
            <p class="slider-subtitle"><?php echo esc_html($subtitle); ?></p>
        <?php endif; ?>

        <?php if (!empty($buttons)): ?>
            <div class="slider-actions">
                <?php foreach ($buttons as $btn): ?>
                    <a class="slider-button <?php echo esc_attr($btn['type']); ?>" href="<?php echo esc_url($btn['url']); ?>">
                        <?php echo esc_html($btn['label']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="slider-container">
        <?php foreach ($images as $index => $img): ?>
            <div class="slider-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                <img src="<?php echo esc_url($img); ?>" alt="">
            </div>
        <?php endforeach; ?>
        <button class="slider-prev <?= $show_navs ? "" : "hidden"; ?>">&#10094;</button>
        <button class="slider-next <?= $show_navs ? "" : "hidden"; ?>">&#10095;</button>
    </div>

    <?php if (count($images) > 1): ?>
    <div class="slider-dots <?= $show_navs ? "" : "hidden"; ?>" role="tablist">
        <?php foreach ($images as $index => $img): ?>
            <button
                class="slider-dot <?php echo $index === 0 ? 'active' : ''; ?>"
                data-target="<?php echo esc_attr($index); ?>"
                aria-label="<?php echo esc_attr(sprintf('Ir a la diapositiva %d', $index + 1)); ?>">
            </button>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
<style>
.slider-wrapper {
    position: relative;
    width: 100%;
    overflow: hidden;
}

/* El contenedor ahora puedes darle la altura que quieras desde fuera */
.slider-container {
    position: relative;
    width: 100%;
    height: 100%; 
    min-height: 420px;
    overflow: hidden;
    max-width: var(--max-width);
}

.slider-slide {
    position: absolute;
    inset: 0;
    opacity: 0;
    transition: opacity 1.2s ease, transform 1.2s ease;
    transform: scale(1.05);
    <?php if(in_array('bw', $images_filter)):?>
        filter: grayscale(100%);
    <?php endif ?>
}

.slider-slide.active {
    opacity: 1;
    transform: scale(1);
}

.slider-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Texto */
.slider-content {
    position: absolute;
    z-index: 20;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    color: #ffffff;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.1);
    padding: 35px 40px;
    border-radius: 12px;
    backdrop-filter: blur(1px);
}

.slider-title {
    font-size: 2.8rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: var(--color-white);
    text-shadow: 2px 2px 6px rgba(0,0,0,0.6);
}

.slider-subtitle {
    font-size: 1.25rem;
    line-height: 1.6;
    opacity: 0.9;
    margin-bottom: 2rem;
    text-shadow: 1px 1px 4px rgba(0,0,0,0.5);
}

/* Botones */
.slider-actions .slider-button {
    display: inline-block;
    padding: 12px 30px;
    margin: 0 10px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: all .3s ease;
    color: var(--color-white);
}

.slider-button.primary {
    background: transparent;
    color: var(--color-white);
    border: 1px solid var(--color-tertiary);
}

.slider-button.primary:hover {
    background: var(--color-tertiary);
    transform: translateY(-2px);
}

.slider-button.secondary {
    background: transparent;
    color: var(--color-white);
    border: 1px solid var(--color-secondary);
}

.slider-button.secondary:hover {
    background: var(--color-secondary);
    transform: translateY(-2px);
}

/* Navegación */
.slider-prev,
.slider-next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: transparent;
    color: var(--color-white) !important;
    font-size: 32px;
    padding: 10px 18px;
    cursor: pointer;
    z-index: 30;
    border-radius: 50%;
    transition: 0.3s ease;
}

.slider-prev:hover, .slider-prev:active, .slider-prev:focus,
.slider-next:hover, .slider-next:active, .slider-next:focus {
    background-color: transparent;
    transform: translateY(-50%) scale(1.1);
}

.slider-prev { left: 20px; }
.slider-next { right: 20px; }

.slider-dots {
    position: absolute;
    z-index: 25;
    bottom: 25px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 10px;
}

.slider-dot {
    all: unset;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 2px solid var(--color-white);
    background: transparent;
    cursor: pointer;
    transition: transform .2s ease, background .2s ease;
}

.slider-dot.active {
    background: var(--color-white);
    transform: scale(1.2);
}

/* Responsive */
@media (max-width: 768px) {
    .slider-content {
        width: 90%;
        padding: 20px;
    }

    .slider-title {
        font-size: 1.8rem;
    }

    .slider-subtitle {
        font-size: 1rem;
    }

    .slider-actions .slider-button {
        display: block;
        margin: 10px auto;
        width: 80%;
    }
}
</style>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let slides = document.querySelectorAll(".slider-slide");
        let dots = document.querySelectorAll(".slider-dot");
        let current = 0;

        function showSlide(index) {
            slides.forEach((s, i) => {
                s.classList.toggle("active", i === index);
            });
            dots.forEach((d, i) => {
                d.classList.toggle("active", i === index);
            });
            current = index;
        }

        document.querySelector(".slider-next").addEventListener("click", () => {
            showSlide((current + 1) % slides.length);
        });

        document.querySelector(".slider-prev").addEventListener("click", () => {
            showSlide((current - 1 + slides.length) % slides.length);
        });

        dots.forEach(dot => {
            dot.addEventListener("click", () => {
                const target = parseInt(dot.dataset.target, 10);
                if (!Number.isNaN(target)) {
                    showSlide(target);
                }
            });
        });

        if (slides.length > 1) {
            setInterval(() => {
                showSlide((current + 1) % slides.length);
            }, 6000);
        }
    });
</script>

