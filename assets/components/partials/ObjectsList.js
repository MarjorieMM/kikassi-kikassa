import React from "react";
import { useState, useEffect } from "react";
import useFetch from "../utils/useFetch";
import ObjectCard from "./ObjectCard";

export function ObjectsList() {
	const { data: objects, error, isLoaded } = useFetch("/api/objets");
	const { data: categories } = useFetch("/api/categories");
	// const sscat = categories.map((cat) =>
	// 	cat.sousCategories.map((c) => c.nom_ss_categorie)
	// );

	// console.log(objects.map((obj) => obj.vitrine));

	if (error) {
		return <div>Erreur: {error}</div>;
	} else if (!isLoaded) {
		return <div>Chargement...</div>;
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
