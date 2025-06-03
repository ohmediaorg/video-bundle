<?php

namespace OHMedia\VideoBundle\Form;

use OHMedia\FileBundle\Form\Type\FileEntityType;
use OHMedia\VideoBundle\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $video = $options['data'];

        $builder->add('url', UrlType::class, [
            'label' => 'URL',
            'mapped' => false,
            'help' => 'Enter a YouTube or Vimeo URL, then use the button to the right to fetch the video information.',
            'data' => $video->getUrl(),
        ]);

        $builder->add('title');

        $builder->add('type', TextType::class);

        $builder->add('video_id', TextType::class, [
            'label' => 'Video ID',
        ]);

        $builder->add('thumbnail', HiddenType::class);
        $builder->add('duration', HiddenType::class);

        $builder->add('image', FileEntityType::class, [
            'label' => 'Override Thumbnail',
            'required' => false,
            'image' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}
