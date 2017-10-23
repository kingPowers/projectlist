<?php

interface Lock{
	
	public function lock();
	public function lockNonBlock();
	public function release();
	
}