import React, {useState, useEffect} from 'react';
import { getMe } from '../services/api';
import "../../styles/me.css"

function Me() {
    const [user, setUser] = useState(null);

    useEffect(() => {
        getMe().then((userData) => {
            setUser(userData);
        });
    }, []);

    return(
        <div>
            {user && (
                <div className="meContainer">
                    {user && (
                        <div>
                            <p>Min / Max : {user.minHours} / {user.maxHours}</p>
                        </div>
                    )}
                </div>
            )}
        </div>
    );
}

export default Me;
