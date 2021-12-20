<?php

/**
 * Updater class for private WordPress plugins.
 * 
 * This class checks if there is any update for a plugin
 * and adds the UI inside Wordpress.
 * 
 * @author Andreas Geyer <andreas@qitone.de>
 * @link https://rudrastyh.com/wordpress/self-hosted-plugin-update.html 
 */

namespace Quantum;

/**
 * Updater class for private WordPress plugins.
 * 
 * This class checks if there is any update for a plugin
 * and adds the UI inside Wordpress.
 * 
 * @version 1.0.0
 * 
 */
class Updater
{
    private $plugin_slug;
    private $version;
    private $cache_key;
    private $cache_allowed;
    private $request_url;

    public function __construct($request_url, $version = '1.0.0', $cache_allowed = false)
    {
        $this->plugin_slug = plugin_basename(__DIR__);
        $this->version = $version;
        $this->cache_key = 'quantum-custom-update-' . $this->plugin_slug;
        $this->cache_allowed = $cache_allowed;
        $this->request_url;

        add_filter('plugins_api', array($this, 'info'), 20, 3);
        add_filter('site_transient_update_plugins', array($this, 'update'));
        add_action('upgrader_process_complete', array($this, 'purge'), 10, 2);
    }

    private function request()
    {

        $remote = get_transient($this->cache_key);

        if (false === $remote || !$this->cache_allowed) {

            $remote = wp_remote_get(
                $this->request_url,
                array(
                    'timeout' => 10,
                    'headers' => array(
                        'Accept' => 'application/json'
                    )
                )
            );

            if (
                is_wp_error($remote)
                || 200 !== wp_remote_retrieve_response_code($remote)
                || empty(wp_remote_retrieve_body($remote))
            ) {
                return false;
            }

            set_transient($this->cache_key, $remote, DAY_IN_SECONDS);
        }

        $remote = json_decode(wp_remote_retrieve_body($remote));

        return $remote;
    }

    public function info($res, $action, $args)
    {

        // print_r( $action );
        // print_r( $args );

        // do nothing if you're not getting plugin information right now
        if ('plugin_information' !== $action) {
            return false;
        }

        // do nothing if it is not our plugin
        if ($this->plugin_slug !== $args->slug) {
            return false;
        }

        // get updates
        $remote = $this->request();

        if (!$remote) {
            return false;
        }

        $res = new \stdClass();

        $res->name = $remote->name;
        $res->slug = $remote->slug;
        $res->version = $remote->version;
        $res->tested = $remote->tested;
        $res->requires = $remote->requires;
        $res->author = $remote->author;
        // $res->author_profile = $remote->author_profile;
        $res->download_link = $remote->download_url;
        $res->trunk = $remote->download_url;
        $res->requires_php = $remote->requires_php;
        $res->last_updated = $remote->last_updated;

        $res->sections = array(
            'description' => $remote->sections->description,
            'installation' => $remote->sections->installation,
            'changelog' => $remote->sections->changelog
        );

        if (!empty($remote->banners)) {
            $res->banners = array(
                'low' => $remote->banners->low,
                'high' => $remote->banners->high
            );
        }

        return $res;
    }

    public function update($transient)
    {

        if (empty($transient->checked)) {
            return $transient;
        }

        $remote = $this->request();

        if (
            $remote
            && version_compare($this->version, $remote->version, '<')
            && version_compare($remote->requires, get_bloginfo('version'), '<')
            && version_compare($remote->requires_php, PHP_VERSION, '<')
        ) {
            $res = new \stdClass();
            $res->slug = $this->plugin_slug;
            $res->plugin = plugin_basename(__FILE__);
            $res->new_version = $remote->version;
            $res->tested = $remote->tested;
            $res->package = $remote->download_url;

            $transient->response[$res->plugin] = $res;
        }

        return $transient;
    }

    public function purge($wp_upgrader, $options)
    {

        if (
            $this->cache_allowed
            && 'update' === $options['action']
            && 'plugin' === $options['type']
        ) {
            // just clean the cache when new plugin version is installed
            delete_transient($this->cache_key);
        }
    }
}
