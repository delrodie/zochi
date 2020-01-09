Integration de cocor/slugify pour le slug <br>

creer la variable <br>
*$slugify = new Slugify()* <br>

puis affecter la valeur du titre a slugifier 
ex: <br>
_$entity->setSlug($slugify->($entity->getTitle());_

Integration de liip/imagineBundle <br>
*liip_imagine.yaml: <br>
thumb:<br>
            quality: 75<br>
            filters:<br>
                thumbnail: { size: [150, 205], mode: inset } #outbound
                background: { size: [150, 205], position: center, color: '#ffffff'}*

frontend: <br>
_<img class="card-img-top" src="{{ asset('/upload/articles/'~article.imageName)|imagine_filter('thumb') }}" alt="Card image cap" width="150">_

vider le cache imagine: <br>
_$cacheManager->remove('/upload/articles/'.$article->getImageFile(), 'thumb');_
