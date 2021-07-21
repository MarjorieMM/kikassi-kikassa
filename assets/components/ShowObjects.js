import { useState, useEffect } from "react";
import React from "react";
import ObjectCard from "./ObjectCard";

export function ShowObjects() {
	const [error, setError] = useState(null);
	const [isLoaded, setIsLoaded] = useState(false);
	// const [items, setItems] = useState([]);
	const [objects, setObjects] = useState([]);
	const [categories, setCategories] = useState([]);

	useEffect(() => {
		const getObjects = async () => {
			const objectsFromServer = await fetchObjects();
			const categoriesFromServer = await fetchCategories();
			setObjects(objectsFromServer["hydra:member"]);
			setCategories(categoriesFromServer["hydra:member"]);
			setIsLoaded(true);
		};
		getObjects();
	}, []);

	const fetchObjects = async () => {
		const res = await fetch("/api/objets");
		const data = await res.json();
		return data;
	};

	const fetchCategories = async () => {
		const res = await fetch("/api/categories");
		const data = await res.json();
		return data;
	};

	const sscat = categories.map((cat) =>
		cat.sousCategories.map((c) => c.nom_ss_categorie)
	);
	// console.log(sscat);

	console.log(categories);
	// useEffect(() => {
	// 	fetch("/api/objets")
	// 		.then((res) => res.json())
	// 		.then(
	// 			(result) => {
	// 				console.log(result);
	// 				setIsLoaded(true);
	// 				setItems(result["hydra:member"]);
	// 				console.log(items);
	// 			},
	// 			// Note: it's important to handle errors here
	// 			// instead of a catch() block so that we don't swallow
	// 			// exceptions from actual bugs in components.
	// 			(error) => {
	// 				setIsLoaded(true);
	// 				setError(error);
	// 			}
	// 		);
	// }, []);

	if (error) {
		return <div>Error: {error.message}</div>;
	} else if (!isLoaded) {
		return <div>Loading...</div>;
	} else {
		return (
			<div className="container">
				<div className="row">
					{objects.map((object) => (
						<div className="col-12 col-md-6 col-lg-4" key={object.id}>
							<ObjectCard
								object={object}
								categories={categories}
								// souscategories={categories.sousCategories}
							/>
						</div>
					))}
				</div>
			</div>
		);
	}
}
