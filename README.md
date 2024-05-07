# Installation

Update `composer.json` by adding this to the `repositories` array:

```json
{
    "type": "vcs",
    "url": "https://github.com/ohmediaorg/video-bundle"
}
```

Then run `composer require ohmediaorg/video-bundle:dev-main`.

Enable the bundle in `config/bundles.php`:

```php
OHMedia\LogoBundle\OHMediaVideoBundle::class => ['all' => true],
```

Import the routes in `config/routes.yaml`:

```yaml
oh_media_video:
    resource: '@OHMediaVideoBundle/config/routes.yaml'
```

Run `php bin/console make:migration` then run the subsequent migration.

# Frontend

Create `templates/bundles/OHMediaVideoBundle/video.html.twig`, which is expected
for rendering the WYSIWYG Twig function `{{ video(id) }}`.

## Plyr Integration

Run `npm install plyr` and add these lines to `assets/frontend/frontend.js`:

```js
import Plyr from 'plyr';

document.querySelectorAll('[data-plyr-embed]').forEach((el) => {
  new Plyr(el);
});
```

Import the styles into a Sass file:

```scss
@import '~plyr';
```

Populate `templates/bundles/OHMediaVideoBundle/video.html.twig`:

```twig
{% if video.typeVimeo %}
<div class="plyr__video-embed" data-plyr-embed>
  <iframe
    src="{{ video.embedUrl }}?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media"
    allowfullscreen
    allowtransparency
    allow="autoplay"
  ></iframe>
</div>
{% elseif video.typeYouTube %}
<div class="plyr__video-embed" data-plyr-embed>
  <iframe
    src="{{ video.embedUrl }}?origin={{ absolute_url('/')|url_encode }}&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1"
    allowfullscreen
    allowtransparency
    allow="autoplay"
  ></iframe>
</div>
{% endif %}
```
