<?php

namespace App\Helpers;

use Vinkla\Hashids\Facades\Hashids;

trait HashingSlug {

    // Attribute used for generating routes
    public function getRouteKeyName()
    {

        return 'hashId';
    }

    // Since "hashid" attribute doesn't "really" exist in
    // database, we generate it dynamically when requested
    public function getHashIdAttribute()
    {

        return Hashids::encode($this->id);
    }

    /**
     * Decodes hash to id
     * @param  string $slug
     * @return int|null
     */
    public static function decodeHash($hashId)
    {

        $decoded = Hashids::decode($hashId);

        if(! isset($decoded[0])){
            return null;
        }

        return (int) $decoded[0];
    }

    // For easy search by hashid
    public function scopeHashId($query, $hashId)
    {
        return $query->where('id', Hashids::decode($hashId)[0]);
    }

    /**
     * Wrapper around Model::findOrFail
     *
     * @param  string $slug
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function findByHashOrFail($hashId)
    {
        $id = self::decodeHash($hashId);

        return static::findOrFail($id);
    }

    /**
     * Wrapper around Model::find
     *
     * @param  string $slug
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public static function findByHash($hashId){

        $id = self::decodeHash($hashId);

        return static::find($id);
    }

}
