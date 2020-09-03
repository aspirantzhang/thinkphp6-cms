<?php

declare(strict_types=1);

namespace app\backend\traits;

trait ModelService
{
    public function saveAPI($data)
    {
        $result = $this->saveNew($data);
        if ($result) {
            return resSuccess('Add successfully.');
        } else {
            return resError($this->error);
        }
    }

    public function deleteAPI($id)
    {
        $group = $this->find($id);
        if ($group) {
            if ($group->delete()) {
                return resSuccess('Delete completed successfully.');
            } else {
                return resError('Delete failed.');
            }
        } else {
            return resError('Target not found.');
        }
    }

    public function batchDeleteAPI($idArray)
    {
        if (count($idArray)) {
            $result = $this->whereIn('id', $idArray)->select()->delete();
            if ($result) {
                return resSuccess('Delete completed successfully.');
            } else {
                return resError('Delete failed.');
            }
        } else {
            return resError('Nothing to do.');
        }
    }
}
