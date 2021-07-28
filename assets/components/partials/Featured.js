import React from "react";
import useFetch from "../utils/useFetch";
import ObjectCard from "./ObjectCard";
import Carousel from "../utils/Carousel";

export default function Featured() {
	const { data: objects, error, isLoaded } = useFetch("/api/objets");
	const { data: categories } = useFetch("/api/categories");

	const tab = [];
	objects.map((obj) => obj.vitrine && tab.push(obj));
	// objects.map((obj) => console.log(obj.vitrine));

	// console.log(tab);

	if (error) {
		return <div>Error: {error.message}</div>;
	} else if (!isLoaded) {
		return <div>Loading...</div>;
	} else {
		return (
			<div>
				<Carousel infiniteLoop={true}>
					{tab.map((object) => (
						<ObjectCard
							list={false}
							key={object.id}
							denomination={object.denomination}
							categories={categories}
							catalogue={object.catalogue.categorie_fourmi}
							photo={
								object.photos.length > 0
									? `photos/${object.photos[0].lien}`
									: "photos/logos/kikassa-default.png"
							}
						/>
					))}
				</Carousel>
			</div>
		);
	}
}
