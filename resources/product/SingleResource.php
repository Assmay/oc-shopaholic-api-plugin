<?php namespace Shohabbos\Shopaholicapi\Resources\Product;

use Illuminate\Http\Resources\Json\Resource;

use Shohabbos\Shopaholicapi\Resources\ImageResource;
use Shohabbos\Shopaholicapi\Resources\ReviewResource;
use Shohabbos\Shopaholicapi\Resources\OfferResource;

class SingleResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'active' => $this->active,
            'name' => $this->name,
            'slug' => $this->slug,
            'code' => $this->code,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'preview_text' => $this->preview_text,
            'description' => $this->description,
            'rating' => $this->rating,
            'rating_data' => $this->rating_data,
            'offer_id_list' => $this->offer_id_list,
            'additional_category_id' => $this->additional_category_id,
            'trashed' => $this->trashed,
            'property_value_array' => $this->property_value_array,

            'offer' => self::collection($this->whenLoaded('offer')),
            'related' => self::collection($this->whenLoaded('related')),
            'accessory' => self::collection($this->whenLoaded('accessory')),
            'review' => ReviewResource::collection($this->whenLoaded('review')),
            'original_image' => new ImageResource($this->whenLoaded('preview_image')),
            'original_images' => ImageResource::collection($this->whenLoaded('images')),
        ];

        // image for preview
        if ($this->preview_image) {
            $data['preview_image'] = $this->preview_image->getThumb(250, 250, ['mode' => 'crop']);
            $data['original_preview_image'] = $this->preview_image->getPath();
        }

        // load offers
        if ($this->offer) {
            $data['offer'] = [];

            foreach ($this->offer as $key => $value) {
                $data['offer'][] = new OfferResource($value);                
            }
        }

        // load offers
        if ($this->offer) {
            $data['price'] = new OfferResource($this->offer()->first());
        }

        // images for gallery
        if ($this->images) {
            $images = [];

            foreach ($this->images as $key => $image) {
                $images[] = [
                    'image' => $image->getThumb(500, 500, ['mode' => 'crop']),
                    'original_image' => $image->getPath()
                ]; 
            }

            $data['images'] = $images;
        }

        return $data;
    }
    
}