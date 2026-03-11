 async function cryptomus(amount) {

                                        const response = await fetch( "<?= $domain ?>server/api/cryptomus-init.php", {
                                            method: "POST",
                                            headers: {
                                                "Content-Type": "application/json"
                                            },
                                            body: JSON.stringify({
                                                amount,
                                                user_id: "<?= $id ?>"
                                            })
                                        });

                                        const data = await response.json();

                                        if (data.status && data.authorization_url) {
                                            window.location.href = data.authorization_url;
                                        } else {
                                            alert("cryptomus error");
                                        }
                                    }