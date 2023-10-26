import React, {useState, useEffect} from 'react';
import {fetchSemesters} from '../services/api';
import {Link} from 'wouter';
import "../../styles/semesterList.css"
import PopUpTags from "./AddTags";
function Semesterlist() {
    const [semesters, setSemesters] = useState(null);

    useEffect(() => {
        fetchSemesters().then((data) => {
            setSemesters(data["hydra:member"]);
        });
    }, []);

    return (
        <div className={"listAndButton"}>
            <div className={"semesterList"}>
                {semesters ===null ? 'Loading...' :
                    semesters.map((semester) => (
                        <div key={semester.id}>
                            <Link href={`/react/semesters/${semester.id}`}>
                                {semester.name}
                            </Link>
                        </div>
                        )
                    )
                }
            </div>
            <div className={"tagsButton"}>
                <PopUpTags/>
            </div>
        </div>
    );
}

export default Semesterlist;
