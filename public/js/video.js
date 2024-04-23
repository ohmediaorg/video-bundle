const tag = document.createElement('script');
tag.src = '//www.youtube.com/iframe_api';
const firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

window.OH_MEDIA_VIDEO_YOUTUBE_API_READY = false;

window.onYouTubeIframeAPIReady = function () {
  window.OH_MEDIA_VIDEO_YOUTUBE_API_READY = true;
};

const youTubeRegex =
  /^.*(?:(?:youtu\.be\/|v\/|vi\/|u\/\w\/|embed\/)|(?:(?:watch)?\?v(?:i)?=|\&v(?:i)?=))([^#\&\?]*).*/;

const vimeoRegex =
  /(http|https)?:\/\/(www|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|video\/|)(\d+)(?:|\/\?)/;

function formatDuration(duration) {
  const minutes = Math.floor(duration / 60);
  let seconds = duration % 60;

  if (seconds < 10) {
    seconds = '0' + seconds;
  }

  return minutes + ':' + seconds;
}

function parseYouTube(url) {
  const matches = url.match(youTubeRegex);

  if (!matches) {
    return new Promise(() => null);
  }

  const id = yt[1];

  const video = {
    type: 'youtube',
    id: id,
    embed: 'https://www.youtube.com/embed/' + id,
    url: 'https://www.youtube.com/watch?v=' + id,
    thumb: null,
    duration: null,
    duration_formatted: null,
  };

  const thumbPromise = new Promise((resolve) => {
    const thumbs = [
      '//img.youtube.com/vi/' + id + '/maxresdefault.jpg',
      '//img.youtube.com/vi/' + id + '/sddefault.jpg',
      '//img.youtube.com/vi/' + id + '/hqdefault.jpg',
      '//img.youtube.com/vi/' + id + '/default.jpg',
      '//img.youtube.com/vi/' + id + '/mqdefault.jpg',
    ];

    function checkThumb(i) {
      if (i >= thumbs.length) {
        resolve();
      }

      const image = new Image();

      image.onload = function (e) {
        video.thumb = image.src;

        if (image.width <= 120 || image.height <= 90) {
          // it's the thumbnail missing image
          // try again
          checkThumb(i + 1);
        } else {
          resolve();
        }
      };

      image.onerror = function (e) {
        checkThumb(i + 1);
      };

      image.src = thumbs[i];
    }

    checkThumb(0);
  });

  let durationPromise = new Promise((resolve) => {
    resolve();
  });

  if (window.OH_MEDIA_VIDEO_YOUTUBE_API_READY) {
    durationPromise = new Promise((resolve) => {
      if (!document.getElementById('OH_MEDIA_VIDEO_YOUTUBE_HIDDEN')) {
        const el = document.createElement('div');
        el.id = 'OH_MEDIA_VIDEO_YOUTUBE_HIDDEN';
        el.style.display = 'none';
        document.body.appendChild(el);
      }

      const player = new YT.Player('OH_MEDIA_VIDEO_YOUTUBE_HIDDEN', {
        height: '390',
        width: '640',
        videoId: id,
        playerVars: {
          autoplay: 0,
        },
        events: {
          onReady: function () {
            video.duration = player.getDuration();
            video.duration_formatted = formatDuration(video.duration);

            player.destroy();

            resolve();
          },
        },
      });
    });
  }

  return Promise.all([durationPromise, thumbPromise]).then(() => video);
}

function parseVimeo(url) {
  const matches = url.match(vimeoRegex);

  if (!matches) {
    return new Promise(() => null);
  }

  const id = vi[4];

  const video = {
    type: 'vimeo',
    id: id,
    embed: 'https://player.vimeo.com/video/' + id,
    url: 'https://vimeo.com/' + id,
    thumb: null,
    duration: null,
    duration_formatted: null,
  };

  return fetch('//vimeo.com/api/v2/video/' + id + '.json')
    .then((data) => {
      video.thumb = data[0].thumbnail_large;
      video.duration = data[0].duration;
      video.duration_formatted = formatDuration(video.duration);
    })
    .catch()
    .finally(() => {
      return video;
    });
}

export default function (url) {
  if (-1 !== url.indexOf('youtu')) {
    return parseYouTube(url);
  } else if (-1 !== url.indexOf('vimeo')) {
    return parseVimeo(url);
  } else {
    return new Promise(() => null);
  }
}
