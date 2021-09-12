import { useState, useEffect } from "react";

const useLogin = (url, credentials) => {
	const [data, setData] = useState([]);
	const [isLoaded, setIsLoaded] = useState(false);
	const [error, setError] = useState(null);

	useEffect(async () => {
		await fetch(url, {
			method: "POST",
			headers: {
				"Content-Type": "application/json",
			},
			body: JSON.stringify(credentials),
		})
			.then((res) => {
				if (!res.ok) {
					throw Error("Couldn't fetch data from database.");
				}
				return res.json();
			})
			.then((data) => {
				setData(data["hydra:member"]);
				setIsLoaded(true);
				setError(null);
			})
			.catch((err) => {
				setIsLoaded(false);
				console.log(err);
				setError("La page ne peut être affichée.");
			});
	}, [url, credentials]);

	return { data, isLoaded, error };
};

export default useLogin;
