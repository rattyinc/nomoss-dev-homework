<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Genre;
use App\Entity\Director;
use App\Entity\Movie;


class MovieController extends AbstractController
{
	/**
	 * @Route("/movie", name="movie")
	 */
	public function index(Request $request)
	{
		$movies = $this->getMovies();
		return $this->render('movie/index.html.twig', array(
			'controller_name' => 'Blep',
			'movies' => $movies
		));
	}

	function importMovieData() {
		$entityManager = $this->getDoctrine()->getManager();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://www.eventcinemas.com.au/Movies/GetNowShowing');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$response = curl_exec($ch);

		$data = json_decode($response);
		$moviesArray = $data->Data->Movies;

		// import directors
		$directorNamesArray = array();
		$directorArray = array();
		foreach($moviesArray as $movie) {
			
			$directorNames = empty($movie->Director) ? "Unknown" : $movie->Director;
			$directorNames = explode(', ', $directorNames);
			foreach($directorNames as $directorName){
				if (!array_search($directorName, $directorNamesArray)) {
					$director = new Director();
					$director->setName($directorName);
					array_push($directorNamesArray, $directorName);
					array_push($directorArray, $director);
					$entityManager->persist($director);
					$entityManager->flush();
				}
			}
		}

//		import genres
		$GenreNamesArray = array();
		$GenreArray = array();
		foreach($moviesArray as $movie) {
			$GenreNames = empty($movie->Genres) ? "Unknown" : $movie->Genres;
			$GenreNames = explode(', ', $GenreNames);
			foreach($GenreNames as $GenreName){
				if (!array_search($GenreName, $GenreNamesArray)) {
					$Genre = new Genre();
					$Genre->setName($GenreName);
					array_push($GenreNamesArray, $Genre->getName());
					array_push($GenreArray, $Genre);
					$entityManager->persist($Genre);
					$entityManager->flush();
				}
			}
		}

		// import movies
		$movieEntityArray = array();
		foreach($moviesArray as $movie) {
			$movieEntity = new Movie();
			$movieEntity->setName($movie->Name);
			$directorName = empty($movie->Director) ? "Unknown" : $movie->Director;
			if (strpos($directorName, ',')) {
				$directorName = explode(', ', $directorName)[count($directorName)];
			}
			$directorId = $this->findDirectorByName($directorName)[0]->getId();
			$movieEntity->setDirectorId($directorId);
			array_push($movieEntityArray, $movieEntity);
			$entityManager->persist($movieEntity);
			$entityManager->flush();
		}
	}

	/**
	 * @Route("/rate_movie", name="_rate_movie")
	 */
	public function rateMovieAction(Request $request)
	{
		$entityManager = $this->getDoctrine()->getManager();
		$rating = $request->get('rating');
		$movieId = $request->get('movieId');
		$movie = $this->getMovieById($movieId);
		if (!empty($movie)) {
			$movie[0]->setRating($rating);
			$entityManager->persist($movie[0]);
			$entityManager->flush();
		} else {
			return new Response("fail");
		}
		return new Response("success");
	}

	public function getMovieById($id) {
		$entityManager = $this->getDoctrine()->getManager();

		$query = $entityManager->createQuery(
			'SELECT m
			FROM App\Entity\Movie m
			WHERE m.id = :id'
		)->setParameter('id', $id);

		// returns an array of Product objects
		return $query->execute();
	}

	public function getMovies(): array {
		$entityManager = $this->getDoctrine()->getManager();
		$query = $entityManager->createQuery(
		'SELECT m.id, m.name, d.name as director, m.rating
		FROM App\Entity\Movie m
		JOIN App\Entity\Director d where d.id = m.director_id');

	// returns an array of Product objects
	return $query->execute();
	}

	public function findDirectorByName($name): array {
	$entityManager = $this->getDoctrine()->getManager();

	$query = $entityManager->createQuery(
		'SELECT d
		FROM App\Entity\Director d
		WHERE d.name = :name'
	)->setParameter('name', $name);

	// returns an array of Product objects
	return $query->execute();
}
}
