
<HTML XMLns="http://www.w3.org/1999/xHTML"> 
  <head> 
    <title>An example of using "for" and "while" in PHP</title> 
  </head> 
  <body>
  <H1>An example of using "for" and "while" in PHP</H1>

  <form>
	    Please input a number:	<input type="text" name="numberfield"> </label> 
		<br/>Select math function: <input type="radio" name="math" value="factorial">factorial &nbsp; <input type="radio" name="math" value="fibonacci">fibonacci 
		<input type="radio" name="math" value="sum"/> Sum <br/>
		<input type="submit" value="Submit" />
  </form>
  
  <p> Result is shown as below.</p>
  </body> 

<?php 
	
	if( isset($_GET['numberfield']) && isset($_GET['math']) )
	{
		$number = $_GET['numberfield'];
		$math = $_GET['math'];
		
		if($math === "factorial")
		{
			echo "The result of factorial($number) is : &nbsp;".factorial($number);
		}
		elseif($math === "fibonacci")
		{
			echo fibonacci($number);
		}
		elseif($math === "sum")
		{
			$result = sum($number); // calculate the sum
			// Display the results
			if($result === -1 ){
				echo "<p> Invalid Input. Input must be > 0</p>";
			} else {
				echo "<p> The sum of numbers from 1 to $number is: $result </p>";
			}
			
		}
		
	}

function sum($number){

	// Case 1: Invalid Input <=0
	if($number <= 0){
		return -1;
	} else {
	// Case 2: Valid Input >=1
	// Generate sum for numbers between 1 and $number (input)
	$total = 0;
	for($i = 1 ; $i <= $number ; $i++){
		$total = $total + $i;
	}
	// Send Response - the sum
	return $total;
	}
	


}

function factorial($number)
{
	if($number > 170){return 0;} //This function can only calculate the factorial of numbers below 171

	$i = 1;
	$result =1;
	while ($i<=$number){
	$result = $result * $i;
	$i++;
	}
	return $result;
}

function fibonacci($number)
{
	$first = 0;
	$second = 1;
	if($number >= 2)
	{
		echo "The first $number fibonacci number are: <br/>";
		echo $first.'<br/>';	
		echo $second.'<br/>';	
		for($i=1;$i<=$number-2;$i++)
		{
			$temp = $first + $second;
			$first = $second;
			$second = $temp;
			echo $temp.'<br/>';			
		}
	}
	elseif ($number==1)
	{
		echo "The first fibonacci number is 0: <br/>";
	}
	else
	{
		echo 'Wrong input number';
	}
}

?>

</HTML>