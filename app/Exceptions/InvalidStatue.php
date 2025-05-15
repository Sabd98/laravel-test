<?php


// app/Exceptions/InvalidStatusTransitionException.php
class InvalidStatusTransitionException extends Exception
{
public function render()
{
return response()->json([
'error' => 'STATUS_TRANSITION_ERROR',
'message' => $this->getMessage()
], 422);
}
}

// app/Exceptions/Handler.php
public function register()
{
$this->renderable(function (InvalidStatusTransitionException $e) {
return response()->json(...);
});
}