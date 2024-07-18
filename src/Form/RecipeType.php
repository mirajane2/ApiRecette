<?php

namespace App\Form;

use App\Entity\Recipe;
use DateTimeImmutable;
// use Doctrine\DBAL\Types\TextType;
use Symfony\Component\EventDispatcher\Attribute\addEventListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Sequentially;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title'
            //  TextType ::class,[
                // 'constraints'=> new Sequentially
                // ([
                //     new Length(min:10),
                //     new Regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$', message: "Ceci  nes pas un slug valide ")
                    
                //  ]),
                
            // ]
            )
            // ->add('createdAt', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('updatedAt', null, [
            //     'widget' => 'single_text',
            // ])
            ->add('duration')
            ->add('content')
            ->add('submit', SubmitType::class,[
                'label'=>'Envoyer'
                ])
        ->addEventListener(FormEvents::POST_SUBMIT, $this->attacheTimeStamps(...))
            // ->addEventListener(FormEvents::PRE_SUBMIT,$this->autoSlug(...))

            
        ;
    }
    // public function autoSlug(PreSubmitEvent $event){
    //     // dd($event->getData());
    //     $data = $event->getData();
    //     if(empty($data['slug'])){
    //         $slugger = new AsciiSlugger();
    //         $data['slug']= strtolower($slugger->slug($data['title']));
    //         $event->setData($data);
    //     }
    // }
public function attacheTimeStamps(PostSubmitEvent $event):void{
    
    $data = $event->getData();
    if(!($data instanceof Recipe)){
        return ;  
    }
    $data->setUpdatedAt(new DateTimeImmutable());
        if(!$data->getId()){
        $data->setCreatedAt(new DateTimeImmutable());
    }
       
    
}



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
            'validation_groups'=>['Default','Extra']
        ]);
    }
}
