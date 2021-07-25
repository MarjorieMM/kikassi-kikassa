import React from "react";
import useFetch from "../utils/useFetch";
import ObjectCard from "./ObjectCard";

export function Featured() {
	const { data: objects, error, isLoaded } = useFetch("/api/objets");

	// const tab = [];
	// objects.map((obj) => obj.vitrine && tab.push(obj));
	// console.log(tab.length);

	if (error) {
		return <div>Error: {error.message}</div>;
	} else if (!isLoaded) {
		return <div>Loading...</div>;
	} else {
		return (
			<div className="container">
				<div className="d-flex flex-row">
					{objects.map(
						(object) =>
							object.vitrine && (
								<div key={object.id}>
									<ObjectCard object={object} />
								</div>
							)
					)}
				</div>
			</div>
		);
	}
}
