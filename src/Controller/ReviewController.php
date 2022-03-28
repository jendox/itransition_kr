<?php
declare(strict_types=1);
namespace App\Controller;

use App\Entity\Image;
use App\Entity\Review;
use App\Entity\Tag;
use App\Entity\User;
use App\Form\CreateReviewType;
use App\Repository\ImageRepository;
use App\Repository\ReviewRepository;
use App\Repository\TagRepository;
use App\Service\FileUploader;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{
//    private string $targetDirectory;

    public function __construct(
//        private ImageRepository $imageRepository,
        private ReviewRepository $reviewRepository,
        private TagRepository $tagRepository
//        private FileUploader $fileUploader
){
//        $this->targetDirectory = $fileUploader->getTargetDirectory();
    }

    /**
     * @throws \League\Flysystem\FilesystemException
     */
//    private function add_images(User $user, Review $review, array $files) {
//        $directory = $user->getEmail();
//        foreach ($files as $file) {
//            $image_name = $this->fileUploader->upload($file, $directory, null);
//            $image = new Image();
//            $image->setName($directory . '/' . $image_name);
//            $image->setReview($review);
//            $review->addImage($image);
//        }
//    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     * @throws \League\Flysystem\FilesystemException
     */
//    private function remove_images(array $ids) {
//        foreach ($ids as $id) {
//                $image = $this->imageRepository->find($id);
//                $this->fileUploader->remove($image->getName());
//                $this->imageRepository->remove($image);
//        }
//    }

    private function get_images_id(Request $request): array {
        $ids = [];
        $params = $request->request;
        foreach ($params as $key => $value) {
            if ($value == 'on') {
                $ids[] = explode('_', $key)[1];
            }
        }
        return $ids;
    }

    private function add_tags(Review $review, $tags_list): void
    {
        $tags_list = json_decode($tags_list, true);
        foreach ($tags_list as $tag_item) {
            $tag = $this->tagRepository->findOneBy(['name' => strtolower($tag_item['value'])]);
            if (!$tag) {
                $tag = new Tag(strtolower($tag_item['value']));
            }
            $review->addTag($tag);
        }
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     * @throws \League\Flysystem\FilesystemException
     */
    #[IsGranted('ROLE_USER')]
    #[Route(
        '/review/create',
        name: 'app_create_review',
        methods: ['get', 'post']
    )]
    public function create(
        Request $request,
    ): Response {

        /** @var User $user */
        $user = $this->getUser();

        $review = new Review();
        $form = $this->createForm(CreateReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $review->setAuthor($user);
            // Add images
//            $files = $form->get('images')->getData();
//            if (count($files)) {
//                $this->add_images($user, $review, $files);
//            }
            // Add tags
            $tags_list = $form->get('tags')->getData();
            if ($tags_list) {
                $this->add_tags($review, $tags_list);
            }
            // Create review
            $this->reviewRepository->add($review);
            $this->addFlash('success', 'The review successfully created!');

            return $this->redirectToRoute('app_create_review');
        }

        $tags = $this->tagRepository->findAll();

        return $this->render('create_review/create.html.twig', [
            'createreviewForm' => $form->createView(),
            'tags' => $tags,
        ]);
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     * @throws \League\Flysystem\FilesystemException
     */
    #[IsGranted('ROLE_USER')]
    #[Route(
        '/review/edit/{id}',
        name: 'app_edit_review',
        requirements: ['id' => '\d+'],
        methods: ['get', 'post']
    )]
    public function edit(
        Review $review,
        Request $request,
        EntityManagerInterface $entityManager): Response {

        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(CreateReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

//            $ids = $this->get_images_id($request);
//            if ($ids) {
//                $this->remove_images($ids);
//            }

//            $files = $form->get('images')->getData();
//            if (count($files)) {
//                $this->add_images($user, $review, $files);
//            }
            $tags_list = $form->get('tags')->getData();
            if ($tags_list) {
                $this->add_tags($review, $tags_list);
            }
            $entityManager->persist($review);
            $entityManager->flush();

            $this->addFlash('success', 'The review successfully edited!');

            return $this->redirectToRoute('app_edit_review', ['id'=> $review->getId()]);
        }

        return $this->render('edit_review/edit.html.twig', [
            'editreviewForm' => $form->createView(),
            'review' => $review,
//            'imagesDirectory' => $this->targetDirectory,
        ]);
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     * @throws \League\Flysystem\FilesystemException
     */
    #[IsGranted('ROLE_USER')]
    #[Route(
        '/review/delete/{id}',
        name: 'app_delete_review',
        requirements: ['id' => '\d+']
    )]
    public function delete(
        Review $review): Response {

//        $images = $review->getImages();
//        $ids = [];
//        foreach ($images as $image) {
//            $ids[] = $image->getId();
//        }
//        if ($ids) {
//            $this->remove_images($ids);
//        }
        $this->reviewRepository->remove($review);
        return $this->redirectToRoute('app_profile', ['user' => $this->getUser()->getId()]);
    }

    #[Route(
        '/review/show/{id}',
        name: 'app_show_review',
        requirements: ['id' => '\d+'],
        methods: ['get']
    )]
    public function show(Review $review, FileUploader $fileUploader): Response
    {
//        $this->targetDirectory = $fileUploader->getTargetDirectory();

        return $this->render('show_review/show.html.twig', [
            'controller_name' => 'ShowReviewController',
            'review' => $review,
//            'imagesDirectory' => $this->targetDirectory,
        ]);
    }

}
