Integration de cocor/slugify pour le slug

creer la variable 
*$slugify = new Slugify()*

puis affecter la valeur du titre a slugifier 
ex: 
_$entity->setSlug($slugify->($entity->getTitle());_
