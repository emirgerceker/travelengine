<?php

/**
 * Elementor widget for displaying custom video.
 *
 * @since 1.0.0
 * @package Togo_Elementor
 */

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;
use Elementor\Utils;

defined('ABSPATH') || exit;

/**
 * Class Togo_Video_Widget
 *
 * Elementor widget for displaying a custom video with title, subtitle, and play button.
 *
 * @since 1.0.0
 */
class Togo_Video_Widget extends Base
{
  /**
   * Widget name.
   */
  const WIDGET_NAME = 'togo-video';

  /**
   * Default video height.
   */
  const DEFAULT_HEIGHT = '650px';

  /**
   * Get the widget name.
   *
   * @return string
   */
  public function get_name()
  {
    return self::WIDGET_NAME;
  }

  /**
   * Get the widget title.
   *
   * @return string
   */
  public function get_title()
  {
    return __('Togo Video', 'togo-framework');
  }

  /**
   * Get the widget icon.
   *
   * @return string
   */
  public function get_icon()
  {
    return 'togo-badge eicon-youtube';
  }

  /**
   * Get the script dependencies of the widget.
   *
   * @return array
   */
  public function get_script_depends()
  {
    return ['togo-widget-video'];
  }

  /**
   * Register the widget controls.
   */
  protected function register_controls()
  {
    $this->add_video_section();
    $this->add_image_overlay_section();
    $this->add_content_style_section();
  }

  /**
   * Add video section controls.
   */
  protected function add_video_section()
  {
    $this->start_controls_section(
      'video_section',
      ['label' => __('Video', 'togo-framework'), 'tab' => Controls_Manager::TAB_CONTENT]
    );

    $this->add_control(
      'video_type',
      [
        'label'   => __('Source', 'togo-framework'),
        'type'    => Controls_Manager::SELECT,
        'default' => 'hosted',
        'options' => [
          'hosted'  => __('Hosted Video', 'togo-framework'),
          'youtube' => __('YouTube', 'togo-framework'),
          'vimeo'   => __('Vimeo', 'togo-framework'),
        ],
      ]
    );

    $this->add_control(
      'hosted_url',
      [
        'label'      => __('Video URL', 'togo-framework'),
        'type'       => Controls_Manager::MEDIA,
        'media_type' => 'video',
        'condition'  => ['video_type' => 'hosted'],
      ]
    );

    $this->add_control(
      'youtube_url',
      [
        'label'       => __('Link', 'togo-framework'),
        'type'        => Controls_Manager::URL,
        'placeholder' => 'https://www.youtube.com/watch?v=XXXXXXXXXXX',
        'condition'   => ['video_type' => 'youtube'],
      ]
    );

    $this->add_control(
      'vimeo_url',
      [
        'label'       => __('Link', 'togo-framework'),
        'type'        => Controls_Manager::URL,
        'placeholder' => 'https://vimeo.com/XXXXXXXXX',
        'condition'   => ['video_type' => 'vimeo'],
      ]
    );

    $this->add_control('autoplay', [
      'label'   => __('Autoplay', 'togo-framework'),
      'type'    => Controls_Manager::SWITCHER,
      'default' => 'no',
    ]);

    $this->add_control('loop', [
      'label'   => __('Loop', 'togo-framework'),
      'type'    => Controls_Manager::SWITCHER,
      'default' => 'no',
    ]);

    $this->add_control('mute', [
      'label'   => __('Mute', 'togo-framework'),
      'type'    => Controls_Manager::SWITCHER,
      'default' => 'no',
    ]);

    $this->add_control('controls', [
      'label'   => __('Player Controls', 'togo-framework'),
      'type'    => Controls_Manager::SWITCHER,
      'default' => 'yes',
    ]);

    $this->add_responsive_control(
      'video_height',
      [
        'label'      => __('Height', 'togo-framework'),
        'type'       => Controls_Manager::SLIDER,
        'size_units' => ['px', '%', 'vw', 'em'],
        'range'      => [
          'px' => ['min' => 200, 'max' => 1000],
          '%'  => ['min' => 0, 'max' => 100],
          'vw' => ['min' => 0, 'max' => 100],
          'em' => ['min' => 0, 'max' => 50],
        ],
        'default'    => ['size' => 650, 'unit' => 'px'],
        'selectors'  => [
          '{{WRAPPER}} .togo-video-widget'         => 'min-height: {{SIZE}}{{UNIT}};',
          '{{WRAPPER}} .togo-video-player video, {{WRAPPER}} .togo-video-player iframe' => 'min-height: {{SIZE}}{{UNIT}};',
        ],
      ]
    );

    $this->end_controls_section();
  }

  /**
   * Add image overlay section controls.
   */
  protected function add_image_overlay_section()
  {
    // Section: Image Overlay
    $this->start_controls_section(
      'section_image_overlay',
      ['label' => __('Image Overlay', 'togo-framework'), 'tab' => Controls_Manager::TAB_CONTENT]
    );

    $this->add_control(
      'show_image_overlay',
      [
        'label'     => __('Image Overlay', 'togo-framework'),
        'type'      => Controls_Manager::SWITCHER,
        'label_on'  => __('Show', 'togo-framework'),
        'label_off' => __('Hide', 'togo-framework'),
        'default'   => 'yes',
      ]
    );

    $this->add_control(
      'image_overlay',
      [
        'label'     => __('Image', 'togo-framework'),
        'type'      => Controls_Manager::MEDIA,
        'default'   => ['url' => Utils::get_placeholder_image_src()],
        'condition' => ['show_image_overlay' => 'yes'],
      ]
    );

    $this->end_controls_section();

    // Section: Play Icon
    $this->start_controls_section(
      'section_play_icon',
      [
        'label'     => __('Play Icon', 'togo-framework'),
        'tab'       => Controls_Manager::TAB_CONTENT,
        'condition' => ['show_image_overlay' => 'yes'],
      ]
    );

    $this->add_control(
      'show_play_icon',
      [
        'label'     => __('Show Play Icon', 'togo-framework'),
        'type'      => Controls_Manager::SWITCHER,
        'label_on'  => __('Show', 'togo-framework'),
        'label_off' => __('Hide', 'togo-framework'),
        'default'   => 'yes',
      ]
    );

    $this->add_control(
      'play_icon_type',
      [
        'label'     => __('Play Icon Type', 'togo-framework'),
        'type'      => Controls_Manager::SELECT,
        'default'   => 'default',
        'options'   => [
          'default' => __('Default', 'togo-framework'),
          'icon'    => __('Icon Library', 'togo-framework'),
          'custom'  => __('Custom SVG', 'togo-framework'),
        ],
        'condition' => ['show_play_icon' => 'yes'],
      ]
    );

    $this->add_control(
      'play_icon',
      [
        'label'     => __('Play Icon', 'togo-framework'),
        'type'      => Controls_Manager::ICONS,
        'default'   => ['value' => 'fas fa-play', 'library' => 'fa-solid'],
        'condition' => ['show_play_icon' => 'yes', 'play_icon_type' => 'icon'],
      ]
    );

    $this->add_control(
      'play_icon_svg',
      [
        'label'     => __('Custom SVG', 'togo-framework'),
        'type'      => Controls_Manager::MEDIA,
        'media_type' => 'svg',
        'default'   => [],
        'condition' => ['show_play_icon' => 'yes', 'play_icon_type' => 'custom'],
      ]
    );

    $this->end_controls_section();

    // Section: Overlay Content (Title, Subtitle, Play Button Title)
    $this->start_controls_section(
      'section_overlay_content',
      [
        'label'     => __('Overlay Content', 'togo-framework'),
        'tab'       => Controls_Manager::TAB_CONTENT,
        'condition' => ['show_image_overlay' => 'yes'],
      ]
    );

    $this->add_control(
      'custom_title',
      [
        'label'     => __('Title', 'togo-framework'),
        'type'      => Controls_Manager::TEXT,
        'default'   => __('Unforgettable travel experiences with a positive impact.', 'togo-framework'),
        'label_block' => true,
      ]
    );

    $this->add_control(
      'custom_subtitle',
      [
        'label'     => __('Subtitle', 'togo-framework'),
        'type'      => Controls_Manager::TEXT,
        'default'   => __('Connecting your journey with purpose', 'togo-framework'),
        'label_block' => true,
      ]
    );

    $this->add_control(
      'play_button_title',
      [
        'label'     => __('Play Button Title', 'togo-framework'),
        'type'      => Controls_Manager::TEXT,
        'default'   => __('Play Video', 'togo-framework'),
      ]
    );

    $this->end_controls_section();
  }

  /**
   * Add content style section controls.
   */
  protected function add_content_style_section()
  {
    // Section: Title Style
    $this->start_controls_section(
      'section_title_style',
      ['label' => __('Title', 'togo-framework'), 'tab' => Controls_Manager::TAB_STYLE]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Typography::get_type(),
      [
        'name'        => 'title_typography',
        'label'       => __('Title Typography', 'togo-framework'),
        'selector'    => '{{WRAPPER}} .togo-video-title',
        'responsive'  => true,
      ]
    );

    $this->add_control(
      'title_color',
      [
        'label'   => __('Title Color', 'togo-framework'),
        'type'    => Controls_Manager::COLOR,
        'default' => '#ffffff',
        'selectors' => ['{{WRAPPER}} .togo-video-title' => 'color: {{VALUE}};'],
      ]
    );

    $this->add_responsive_control(
      'title_align',
      [
        'label'   => __('Title Alignment', 'togo-framework'),
        'type'    => Controls_Manager::CHOOSE,
        'options' => [
          'left'   => ['title' => __('Left', 'togo-framework'), 'icon' => 'eicon-text-align-left'],
          'center' => ['title' => __('Center', 'togo-framework'), 'icon' => 'eicon-text-align-center'],
          'right'  => ['title' => __('Right', 'togo-framework'), 'icon' => 'eicon-text-align-right'],
        ],
        'default' => 'left',
        'selectors' => ['{{WRAPPER}} .togo-video-title' => 'text-align: {{VALUE}};'],
      ]
    );

    $this->end_controls_section();

    // Section: Subtitle Style
    $this->start_controls_section(
      'section_subtitle_style',
      ['label' => __('Subtitle', 'togo-framework'), 'tab' => Controls_Manager::TAB_STYLE]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Typography::get_type(),
      [
        'name'        => 'subtitle_typography',
        'label'       => __('Subtitle Typography', 'togo-framework'),
        'selector'    => '{{WRAPPER}} .togo-video-subtitle',
        'responsive'  => true,
      ]
    );

    $this->add_control(
      'subtitle_color',
      [
        'label'   => __('Subtitle Color', 'togo-framework'),
        'type'    => Controls_Manager::COLOR,
        'default' => '#ffffff',
        'selectors' => ['{{WRAPPER}} .togo-video-subtitle' => 'color: {{VALUE}};'],
      ]
    );

    $this->add_responsive_control(
      'subtitle_align',
      [
        'label'   => __('Subtitle Alignment', 'togo-framework'),
        'type'    => Controls_Manager::CHOOSE,
        'options' => [
          'left'   => ['title' => __('Left', 'togo-framework'), 'icon' => 'eicon-text-align-left'],
          'center' => ['title' => __('Center', 'togo-framework'), 'icon' => 'eicon-text-align-center'],
          'right'  => ['title' => __('Right', 'togo-framework'), 'icon' => 'eicon-text-align-right'],
        ],
        'default' => 'left',
        'selectors' => ['{{WRAPPER}} .togo-video-subtitle' => 'text-align: {{VALUE}};'],
      ]
    );

    $this->end_controls_section();

    // Section: Play Button Style
    $this->start_controls_section(
      'section_play_button_style',
      ['label' => __('Play Button', 'togo-framework'), 'tab' => Controls_Manager::TAB_STYLE]
    );

    $this->add_control(
      'play_button_color',
      [
        'label'   => __('Play Button Color', 'togo-framework'),
        'type'    => Controls_Manager::COLOR,
        'default' => '#ffffff',
        'selectors' => ['{{WRAPPER}} .togo-play-button' => 'background-color: {{VALUE}};'],
      ]
    );

    $this->add_control(
      'play_icon_color',
      [
        'label'   => __('Play Icon Color', 'togo-framework'),
        'type'    => Controls_Manager::COLOR,
        'default' => '#fd4621',
        'selectors' => ['{{WRAPPER}} .togo-play-button svg path' => 'stroke: {{VALUE}};', '{{WRAPPER}} .togo-play-button i' => 'color: {{VALUE}};'],
      ]
    );

    $this->add_responsive_control(
      'play_button_size',
      [
        'label'      => __('Play Button Size', 'togo-framework'),
        'type'       => Controls_Manager::SLIDER,
        'size_units' => ['px', '%', 'vw'],
        'range'      => [
          'px' => ['min' => 20, 'max' => 150],
          '%'  => ['min' => 0, 'max' => 20],
          'vw' => ['min' => 0, 'max' => 20],
        ],
        'default'    => ['size' => 60, 'unit' => 'px'],
        'selectors'  => [
          '{{WRAPPER}} .togo-play-button' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
          '{{WRAPPER}} .togo-play-button svg, {{WRAPPER}} .togo-play-button i' => 'width: calc({{SIZE}}{{UNIT}} * 0.5); height: calc({{SIZE}}{{UNIT}} * 0.5);',
        ],
      ]
    );

    $this->add_responsive_control(
      'play_button_padding',
      [
        'label'      => __('Play Button Padding', 'togo-framework'),
        'type'       => Controls_Manager::SLIDER,
        'size_units' => ['px', '%', 'vw'],
        'range'      => [
          'px' => ['min' => 0, 'max' => 50],
          '%'  => ['min' => 0, 'max' => 20],
          'vw' => ['min' => 0, 'max' => 20],
        ],
        'default'    => ['size' => 10, 'unit' => 'px'],
        'selectors'  => ['{{WRAPPER}} .togo-play-button' => 'padding: {{SIZE}}{{UNIT}};'],
      ]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Box_Shadow::get_type(),
      [
        'name'     => 'play_button_shadow',
        'label'    => __('Play Button Shadow', 'togo-framework'),
        'selector' => '{{WRAPPER}} .togo-play-button',
      ]
    );

    $this->end_controls_section();

    // Section: Play Button Text Style
    $this->start_controls_section(
      'section_play_button_text_style',
      ['label' => __('Play Button Text', 'togo-framework'), 'tab' => Controls_Manager::TAB_STYLE]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Typography::get_type(),
      [
        'name'        => 'play_button_text_typography',
        'label'       => __('Text Typography', 'togo-framework'),
        'selector'    => '{{WRAPPER}} .togo-play-button-text',
        'responsive'  => true,
      ]
    );

    $this->add_control(
      'play_button_text_color',
      [
        'label'   => __('Text Color', 'togo-framework'),
        'type'    => Controls_Manager::COLOR,
        'default' => '#ffffff',
        'selectors' => ['{{WRAPPER}} .togo-play-button-text' => 'color: {{VALUE}};'],
      ]
    );

    $this->add_responsive_control(
      'play_button_text_align',
      [
        'label'   => __('Text Alignment', 'togo-framework'),
        'type'    => Controls_Manager::CHOOSE,
        'options' => [
          'left'   => ['title' => __('Left', 'togo-framework'), 'icon' => 'eicon-text-align-left'],
          'center' => ['title' => __('Center', 'togo-framework'), 'icon' => 'eicon-text-align-center'],
          'right'  => ['title' => __('Right', 'togo-framework'), 'icon' => 'eicon-text-align-right'],
        ],
        'default' => 'left',
        'selectors' => ['{{WRAPPER}} .togo-play-button-text' => 'text-align: {{VALUE}};'],
      ]
    );

    $this->add_responsive_control(
      'play_button_text_margin',
      [
        'label'      => __('Text Margin', 'togo-framework'),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em'],
        'default'    => [
          'top'    => '0',
          'right'  => '0',
          'bottom' => '0',
          'left'   => '10',
          'unit'   => 'px',
          'isLinked' => false,
        ],
        'selectors'  => ['{{WRAPPER}} .togo-play-button-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
      ]
    );

    $this->end_controls_section();

    // Section: Overlay Container Style
    $this->start_controls_section(
      'section_overlay_container_style',
      ['label' => __('Overlay Container', 'togo-framework'), 'tab' => Controls_Manager::TAB_STYLE]
    );

    $this->add_responsive_control(
      'content_width',
      [
        'label'      => __('Content Width', 'togo-framework'),
        'type'       => Controls_Manager::SLIDER,
        'size_units' => ['px', '%', 'vw', 'em'],
        'range'      => [
          'px' => ['min' => 0, 'max' => 1000],
          '%'  => ['min' => 0, 'max' => 100],
          'vw' => ['min' => 0, 'max' => 100],
          'em' => ['min' => 0, 'max' => 50],
        ],
        'default'    => ['size' => 100, 'unit' => '%'],
        'selectors'  => ['{{WRAPPER}} .togo-video-content' => 'width: {{SIZE}}{{UNIT}};'],
      ]
    );

    $this->add_responsive_control(
      'overlay_padding',
      [
        'label'      => __('Overlay Padding', 'togo-framework'),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em'],
        'default'    => [
          'top'    => '20',
          'right'  => '20',
          'bottom' => '20',
          'left'   => '20',
          'unit'   => 'px',
          'isLinked' => true,
        ],
        'selectors'  => ['{{WRAPPER}} .togo-video-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
      ]
    );

    $this->add_responsive_control(
      'overlay_margin',
      [
        'label'      => __('Overlay Margin', 'togo-framework'),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em'],
        'default'    => [
          'top'    => '0',
          'right'  => '0',
          'bottom' => '0',
          'left'   => '0',
          'unit'   => 'px',
          'isLinked' => true,
        ],
        'selectors'  => ['{{WRAPPER}} .togo-video-overlay' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
      ]
    );

    $this->add_responsive_control(
      'overlay_vertical_align',
      [
        'label'   => __('Vertical Align', 'togo-framework'),
        'type'    => Controls_Manager::CHOOSE,
        'options' => [
          'flex-start' => ['title' => __('Top', 'togo-framework'), 'icon' => 'eicon-v-align-top'],
          'center'     => ['title' => __('Middle', 'togo-framework'), 'icon' => 'eicon-v-align-middle'],
          'flex-end'   => ['title' => __('Bottom', 'togo-framework'), 'icon' => 'eicon-v-align-bottom'],
        ],
        'default' => 'flex-start',
        'selectors' => ['{{WRAPPER}} .togo-video-overlay' => 'justify-content: {{VALUE}};'],
      ]
    );

    $this->add_responsive_control(
      'overlay_horizontal_align',
      [
        'label'   => __('Horizontal Align', 'togo-framework'),
        'type'    => Controls_Manager::CHOOSE,
        'options' => [
          'flex-start' => ['title' => __('Left', 'togo-framework'), 'icon' => 'eicon-h-align-left'],
          'center'     => ['title' => __('Center', 'togo-framework'), 'icon' => 'eicon-h-align-center'],
          'flex-end'   => ['title' => __('Right', 'togo-framework'), 'icon' => 'eicon-h-align-right'],
        ],
        'default' => 'flex-start',
        'selectors' => ['{{WRAPPER}} .togo-video-overlay' => 'align-items: {{VALUE}};'],
      ]
    );

    $this->end_controls_section();
  }

 /**
   * Render the widget output.
   */
  protected function render()
  {
    $settings = $this->get_settings_for_display();
    $videoAttrs = $this->get_video_attributes($settings);
    $videoContent = $this->get_video_content($settings, $videoAttrs['url']);
    $overlayContent = $this->get_overlay_content($settings);
    $styles = $this->get_styles($settings);
?>

    <div class="togo-video-widget <?php echo esc_attr($styles['class']); ?>"
      data-autoplay="<?php echo esc_attr($settings['autoplay']); ?>"
      data-loop="<?php echo esc_attr($settings['loop']); ?>"
      data-mute="<?php echo esc_attr($settings['mute']); ?>"
      data-controls="<?php echo esc_attr($settings['controls']); ?>">
      <?php if ($overlayContent['show'] && ($settings['custom_subtitle'] || $settings['custom_title'])) : ?>
        <div class="togo-video-overlay">
          <div class="togo-video-content">
            <?php if ($settings['custom_subtitle']) : ?>
              <p class="togo-video-subtitle"><?php echo esc_html($settings['custom_subtitle']); ?></p>
            <?php endif; ?>
            <?php if ($settings['custom_title']) : ?>
              <h2 class="togo-video-title"><?php echo esc_html($settings['custom_title']); ?></h2>
            <?php endif; ?>
          </div>
          <?php if ($overlayContent['playIcon']) : ?>
            <div class="togo-video-play-icon">
              <button class="togo-play-button" aria-label="<?php echo esc_attr($settings['play_button_title'] ?: __('Play Video', 'togo-framework')); ?>">
                <?php $this->render_play_icon($settings); ?>
              </button>
              <?php if ($settings['play_button_title']) : ?>
                <span class="togo-play-button-text"><?php echo esc_html($settings['play_button_title']); ?></span>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>
      <div class="togo-video-player" style="<?php echo esc_attr(!$overlayContent['show'] || !($settings['custom_subtitle'] || $settings['custom_title']) ? '' : 'display:none;'); ?>">
        <?php if ($videoContent) : ?>
          <?php echo $videoContent; ?>
        <?php else : ?>
          <div class="togo-video-placeholder"><?php _e('No video source available. Please add a video URL.', 'togo-framework'); ?></div>
        <?php endif; ?>
      </div>
    </div>

    <style>
      <?php echo $styles['css']; ?>
    </style>
    <?php
  }
  /**
   * Get video attributes.
   *
   * @param array $settings Widget settings.
   * @return array
   */
  private function get_video_attributes($settings)
  {
    $videoTypes = ['hosted' => 'hosted_url', 'youtube' => 'youtube_url', 'vimeo' => 'vimeo_url'];
    $videoType = $settings['video_type'];
    $url = !empty($settings[$videoTypes[$videoType]]['url']) ? $settings[$videoTypes[$videoType]]['url'] : '';

    return [
      'url'      => $url,
      'autoplay' => $settings['autoplay'] === 'yes' ? 'autoplay' : '',
      'loop'     => $settings['loop'] === 'yes' ? 'loop' : '',
      'mute'     => $settings['mute'] === 'yes' ? 'muted' : '',
      'controls' => $settings['controls'] === 'yes' ? 'controls' : '',
      'height'   => isset($settings['video_height']['size'], $settings['video_height']['unit'])
        ? $settings['video_height']['size'] . $settings['video_height']['unit']
        : self::DEFAULT_HEIGHT,
    ];
  }

  /**
   * Get video content based on type.
   *
   * @param array $settings Widget settings.
   * @param string $url Video URL.
   * @return string
   */
  private function get_video_content($settings, $url)
  {
    if (!$url) {
      return '<div style="color: #111111;">Please add a video URL.</div>';
    }

    $videoType = $settings['video_type'];
    $attrs = $this->get_video_attributes($settings);
    $videoId = $videoType === 'youtube' ? $this->get_youtube_id($url) : ($videoType === 'vimeo' ? $this->get_vimeo_id($url) : '');

    ob_start();
    if ($videoType === 'hosted') : ?>
      <video <?php echo esc_attr($attrs['controls']); ?> <?php echo esc_attr($attrs['autoplay']); ?>
        <?php echo esc_attr($attrs['loop']); ?> <?php echo esc_attr($attrs['mute']); ?>>
        <source src="<?php echo esc_url($url); ?>" type="video/mp4">
        <?php _e('Your browser does not support the video tag.', 'togo-framework'); ?>
      </video>
    <?php elseif ($videoId && $videoType === 'youtube') : ?>
      <iframe id="ytplayer-<?php echo esc_attr($this->get_id()); ?>"
        src="https://www.youtube.com/embed/<?php echo esc_attr($videoId); ?>?autoplay=0&loop=<?php echo $attrs['loop'] ? 1 : 0; ?>&muted=<?php echo $attrs['mute'] ? 1 : 0; ?>&playlist=<?php echo esc_attr($videoId); ?>&enablejsapi=1&disablekb=1&iv_load_policy=3"
        frameborder="0" allowfullscreen></iframe>
    <?php elseif ($videoId && $videoType === 'vimeo') : ?>
      <iframe src="https://player.vimeo.com/video/<?php echo esc_attr($videoId); ?>?autoplay=0&loop=<?php echo $attrs['loop'] ? 1 : 0; ?>&muted=<?php echo $attrs['mute'] ? 1 : 0; ?>" frameborder="0" allowfullscreen></iframe>
    <?php endif;
    return ob_get_clean();
  }

  /**
   * Get overlay content settings.
   *
   * @param array $settings Widget settings.
   * @return array
   */
  private function get_overlay_content($settings)
  {
    return [
      'show'     => $settings['show_image_overlay'] === 'yes',
      'image'    => !empty($settings['image_overlay']['url']) ? $settings['image_overlay']['url'] : '',
      'playIcon' => $settings['show_play_icon'] === 'yes',
    ];
  }

  /**
   * Get inline styles.
   *
   * @param array $settings Widget settings.
   * @return array
   */
  private function get_styles($settings)
  {
    $overlay = $this->get_overlay_content($settings);
    $class = '';
    $css = '';

    if ($overlay['image'] && $overlay['show']) {
      $class = 'togo-video-bg';
      $css = "
          .togo-video-bg {
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.5) 50%, rgba(0, 0, 0, 0) 100%), url('" . esc_url($overlay['image']) . "') no-repeat center center;
            background-size: cover;
            position: relative;
          }
        ";
    }

    return [
      'class' => $class,
      'css' => $css
    ];
  }

  /**
   * Render play icon based on type.
   *
   * @param array $settings Widget settings.
   */
  private function render_play_icon($settings)
  {
    $playIconType = $settings['play_icon_type'];
    if ($playIconType === 'default') : ?>
      <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
        <path d="M13.3669 8.61739C14.845 9.82309 15.584 10.4259 15.584 11.457C15.584 12.4881 14.845 13.091 13.3669 14.2967C12.9589 14.6295 12.5543 14.9429 12.1824 15.204C11.8561 15.4331 11.4867 15.6701 11.1041 15.9027C9.62959 16.7994 8.89231 17.2477 8.23105 16.7513C7.5698 16.2549 7.5097 15.2158 7.38951 13.1375C7.35552 12.5497 7.33398 11.9736 7.33398 11.457C7.33398 10.9405 7.35552 10.3643 7.38951 9.77657C7.5097 7.69827 7.5698 6.65912 8.23106 6.16273C8.89231 5.66634 9.62959 6.11468 11.1041 7.01137C11.4867 7.24399 11.8561 7.48096 12.1824 7.71004C12.5543 7.97117 12.9589 8.28455 13.3669 8.61739Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
    <?php elseif ($playIconType === 'custom' && !empty($settings['play_icon_svg']['url'])) : ?>
      <img src="<?php echo esc_url($settings['play_icon_svg']['url']); ?>" alt="<?php echo esc_attr($settings['play_button_title'] ?: __('Play Video', 'togo-framework')); ?>" style="width: 33%; height: 33%;">
<?php else :
      \Elementor\Icons_Manager::render_icon($settings['play_icon'], ['aria-hidden' => 'true']);
    endif;
  }

  /**
   * Get YouTube video ID from URL.
   *
   * @param string $url YouTube video URL.
   * @return string|null
   */
  protected function get_youtube_id($url)
  {
    $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i';
    return preg_match($pattern, $url, $match) ? $match[1] : null;
  }

  /**
   * Get Vimeo video ID from URL.
   *
   * @param string $url Vimeo video URL.
   * @return string|null
   */
  protected function get_vimeo_id($url)
  {
    $pattern = '/vimeo\.com\/([0-9]+)/i';
    return preg_match($pattern, $url, $match) ? $match[1] : null;
  }
}
